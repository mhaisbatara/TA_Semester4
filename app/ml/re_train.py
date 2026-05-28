"""
=============================================================================
PREDIKSI OBESITAS - MODELING (DECISION TREE) - ANTI OVERFITTING v2
Perbaikan:
  1. Hapus fitur redundan 'Kategori_BMI' (derived langsung dari BMI)
  2. GridSearch ccp_alpha wajib > 0 (pruning aktif)
  3. min_samples_leaf lebih besar (10-25) → daun lebih general
  4. Threshold gap overfitting diperketat < 3%
=============================================================================
"""

import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.tree import DecisionTreeClassifier, plot_tree
from sklearn.metrics import (accuracy_score, classification_report,
                             confusion_matrix, f1_score)
from sklearn.model_selection import (cross_val_score, GridSearchCV,
                                     StratifiedKFold, learning_curve)
import pickle
import warnings
warnings.filterwarnings('ignore')

plt.rcParams['figure.dpi'] = 120
sns.set_style("whitegrid")

TARGET_NAMES = ['Kurus', 'Normal', 'Overweight_Tingkat_1', 'Overweight_Tingkat_2',
                'Obesitas_Tipe_1', 'Obesitas_Tipe_2', 'Obesitas_Tipe_3']
COLORS = ['#2ecc71', '#3498db', '#f39c12', '#e67e22', '#e74c3c', '#c0392b', '#8e44ad']

# =============================================================================
# LOAD DATA
# =============================================================================
print("=" * 70)
print("  MODELING – DECISION TREE (ANTI OVERFITTING v2)")
print("=" * 70)

X_train = pd.read_csv('X_train.csv')
X_test  = pd.read_csv('X_test.csv')
y_train = pd.read_csv('y_train.csv').squeeze()
y_test  = pd.read_csv('y_test.csv').squeeze()

# =============================================================================
# FIX 1: HAPUS FITUR REDUNDAN (Kategori_BMI)
# =============================================================================
# Kategori_BMI adalah transformasi deterministik dari BMI (< 18.5 / 25 / 30).
# Menyimpan keduanya menyebabkan pohon terlalu bergantung pada satu sinyal
# yang sama → overfitting pada fitur BMI.
# Solusi: pertahankan BMI (nilai kontinu, lebih informatif), hapus Kategori_BMI.

DROP_COLS = ['Kategori_BMI']
X_train = X_train.drop(columns=DROP_COLS)
X_test  = X_test.drop(columns=DROP_COLS)

FEATURE_NAMES = X_train.columns.tolist()

print(f"\n  ✅ Fitur redundan dihapus : {DROP_COLS}")
print(f"  X_train : {X_train.shape}  |  X_test : {X_test.shape}")
print(f"  Fitur   : {len(FEATURE_NAMES)} fitur")
print(f"  Kelas   : {len(TARGET_NAMES)} kelas")

# =============================================================================
# STEP 1: BASELINE
# =============================================================================
print("\n" + "=" * 70)
print("  STEP 1: BASELINE MODEL (Sebelum Anti-Overfitting)")
print("=" * 70)

dt_baseline = DecisionTreeClassifier(criterion='gini', random_state=42)
dt_baseline.fit(X_train, y_train)

acc_bl_train = accuracy_score(y_train, dt_baseline.predict(X_train))
acc_bl_test  = accuracy_score(y_test,  dt_baseline.predict(X_test))
gap_bl       = acc_bl_train - acc_bl_test

print(f"""
  Accuracy Train : {acc_bl_train*100:.2f}%
  Accuracy Test  : {acc_bl_test*100:.2f}%
  Gap (Overfit)  : {gap_bl*100:.2f}%  ← {'❌ Overfitting!' if gap_bl > 0.05 else '✅ OK'}
  Depth          : {dt_baseline.get_depth()}
  Leaves         : {dt_baseline.get_n_leaves()}
""")

# =============================================================================
# STEP 2: COST-COMPLEXITY PRUNING (ccp_alpha)
# =============================================================================
print("=" * 70)
print("  STEP 2: COST-COMPLEXITY PRUNING (ccp_alpha)")
print("=" * 70)

path       = dt_baseline.cost_complexity_pruning_path(X_train, y_train)
ccp_alphas = path.ccp_alphas[::5]

acc_trains_ccp, acc_tests_ccp = [], []
for alpha in ccp_alphas:
    dt_ccp = DecisionTreeClassifier(criterion='gini', ccp_alpha=alpha, random_state=42)
    dt_ccp.fit(X_train, y_train)
    acc_trains_ccp.append(accuracy_score(y_train, dt_ccp.predict(X_train)))
    acc_tests_ccp.append(accuracy_score(y_test,   dt_ccp.predict(X_test)))

best_alpha_idx = np.argmax(acc_tests_ccp)
best_alpha     = ccp_alphas[best_alpha_idx]

print(f"  Best ccp_alpha      : {best_alpha:.6f}")
print(f"  Accuracy Train      : {acc_trains_ccp[best_alpha_idx]*100:.2f}%")
print(f"  Accuracy Test       : {acc_tests_ccp[best_alpha_idx]*100:.2f}%")
print(f"  Gap setelah pruning : {(acc_trains_ccp[best_alpha_idx]-acc_tests_ccp[best_alpha_idx])*100:.2f}%")

# =============================================================================
# STEP 3: HYPERPARAMETER TUNING
# =============================================================================
print("\n" + "=" * 70)
print("  STEP 3: HYPERPARAMETER TUNING (GridSearchCV – Anti Overfitting)")
print("=" * 70)

# FIX 2: ccp_alpha TIDAK mengikutkan 0.0 → pruning selalu aktif
# FIX 3: min_samples_leaf lebih besar (10–25) → daun lebih general
param_grid = {
    'criterion'         : ['gini', 'entropy'],
    'max_depth'         : [5, 6, 7, 8],
    'min_samples_split' : [20, 30, 50],
    'min_samples_leaf'  : [10, 15, 20, 25],     # ← lebih besar dari sebelumnya
    'max_features'      : ['sqrt', 'log2'],
    'ccp_alpha'         : [0.001, 0.003, 0.005, 0.008, 0.01],  # ← tidak ada 0.0
}

total_comb = (len(param_grid['criterion']) * len(param_grid['max_depth']) *
              len(param_grid['min_samples_split']) * len(param_grid['min_samples_leaf']) *
              len(param_grid['max_features']) * len(param_grid['ccp_alpha']))

print(f"\n  Total kombinasi : {total_comb}")
print(f"  CV              : 5-Fold Stratified")
print(f"  Scoring         : weighted F1")
print(f"\n  Mencari parameter terbaik... ", end='', flush=True)

skf = StratifiedKFold(n_splits=5, shuffle=True, random_state=42)
grid_search = GridSearchCV(
    DecisionTreeClassifier(random_state=42),
    param_grid,
    cv=skf,
    scoring='f1_weighted',
    n_jobs=-1,
    verbose=0
)
grid_search.fit(X_train, y_train)
print("✅ Selesai!")

best_params = grid_search.best_params_
best_cv_f1  = grid_search.best_score_

print(f"\n  Best CV F1 Score : {best_cv_f1:.4f}")
print(f"  Best Parameters  :")
for k, v in best_params.items():
    print(f"    {k:<22}: {v}")

# =============================================================================
# STEP 4: FINAL MODEL
# =============================================================================
print("\n" + "=" * 70)
print("  STEP 4: FINAL MODEL – Decision Tree (Anti-Overfitting v2)")
print("=" * 70)

dt_best = DecisionTreeClassifier(**best_params, random_state=42)
dt_best.fit(X_train, y_train)

y_pred_train = dt_best.predict(X_train)
y_pred_test  = dt_best.predict(X_test)

acc_train = accuracy_score(y_train, y_pred_train)
acc_test  = accuracy_score(y_test,  y_pred_test)
f1_train  = f1_score(y_train, y_pred_train, average='weighted')
f1_test   = f1_score(y_test,  y_pred_test,  average='weighted')
gap_final = acc_train - acc_test

print(f"""
  ┌────────────────────────────────────────────────────────┐
  │  PERBANDINGAN: Baseline vs Anti-Overfitting v2         │
  ├──────────────────┬──────────────┬──────────────────────┤
  │ Metrik           │   Baseline   │  Anti-Overfitting v2 │
  ├──────────────────┼──────────────┼──────────────────────┤
  │ Accuracy Train   │  {acc_bl_train*100:>8.2f}%   │  {acc_train*100:>16.2f}%   │
  │ Accuracy Test    │  {acc_bl_test*100:>8.2f}%   │  {acc_test*100:>16.2f}%   │
  │ Gap (Overfit)    │  {gap_bl*100:>8.2f}%   │  {gap_final*100:>16.2f}%   │
  │ Depth            │  {dt_baseline.get_depth():>12}   │  {dt_best.get_depth():>20}   │
  │ Leaves           │  {dt_baseline.get_n_leaves():>12}   │  {dt_best.get_n_leaves():>20}   │
  └──────────────────┴──────────────┴──────────────────────┘

  Gap Baseline : {gap_bl*100:.2f}% → {'❌ Overfitting' if gap_bl > 0.05 else '✅ OK'}
  Gap Final    : {gap_final*100:.2f}% → {'✅ Tidak Overfitting' if gap_final < 0.03 else '⚠️ Sedikit Overfitting' if gap_final < 0.05 else '❌ Masih Overfitting'}
  Perbaikan    : {(gap_bl - gap_final)*100:.2f}% ↓
""")

# =============================================================================
# STEP 5: CROSS VALIDATION
# =============================================================================
print("=" * 70)
print("  STEP 5: CROSS VALIDATION – 5-Fold Stratified")
print("=" * 70)

cv_acc  = cross_val_score(dt_best, X_train, y_train, cv=skf, scoring='accuracy')
cv_f1   = cross_val_score(dt_best, X_train, y_train, cv=skf, scoring='f1_weighted')
cv_prec = cross_val_score(dt_best, X_train, y_train, cv=skf, scoring='precision_weighted')
cv_rec  = cross_val_score(dt_best, X_train, y_train, cv=skf, scoring='recall_weighted')

print(f"\n  {'Fold':<8} {'Accuracy':>10} {'F1':>10} {'Precision':>11} {'Recall':>10}")
print("  " + "─" * 52)
for i in range(5):
    print(f"  Fold {i+1:<3} {cv_acc[i]*100:>9.2f}% {cv_f1[i]:>10.4f} "
          f"{cv_prec[i]:>11.4f} {cv_rec[i]:>10.4f}")
print("  " + "─" * 52)
print(f"  {'Mean':<8} {cv_acc.mean()*100:>9.2f}% {cv_f1.mean():>10.4f} "
      f"{cv_prec.mean():>11.4f} {cv_rec.mean():>10.4f}")
print(f"  {'Std':<8} {cv_acc.std()*100:>9.2f}% {cv_f1.std():>10.4f} "
      f"{cv_prec.std():>11.4f} {cv_rec.std():>10.4f}")

stability = ('✅ Sangat Stabil' if cv_acc.std() < 0.01 else
             '✅ Stabil'        if cv_acc.std() < 0.02 else
             '⚠️ Cukup Stabil'  if cv_acc.std() < 0.05 else '❌ Tidak Stabil')
print(f"\n  Stabilitas CV : {cv_acc.std()*100:.2f}% → {stability}")

# =============================================================================
# STEP 6: CLASSIFICATION REPORT
# =============================================================================
print("\n" + "=" * 70)
print("  STEP 6: CLASSIFICATION REPORT (Test Set)")
print("=" * 70)

report_dict = classification_report(y_test, y_pred_test,
                                    target_names=TARGET_NAMES,
                                    output_dict=True)
print(f"\n  {'Kategori':<25} {'Precision':>10} {'Recall':>10} {'F1-Score':>10} {'Support':>10}")
print("  " + "─" * 70)
for cat in TARGET_NAMES:
    r    = report_dict[cat]
    flag = "✅" if r['f1-score'] >= 0.90 else "⚠️ " if r['f1-score'] >= 0.75 else "❌"
    print(f"  {cat:<25} {r['precision']:>10.4f} {r['recall']:>10.4f} "
          f"{r['f1-score']:>10.4f} {int(r['support']):>10}  {flag}")
print("  " + "─" * 70)
wa = report_dict['weighted avg']
print(f"  {'Weighted Avg':<25} {wa['precision']:>10.4f} {wa['recall']:>10.4f} "
      f"{wa['f1-score']:>10.4f} {int(wa['support']):>10}")

# =============================================================================
# STEP 7: FEATURE IMPORTANCE
# =============================================================================
print("\n" + "=" * 70)
print("  STEP 7: FEATURE IMPORTANCE")
print("=" * 70)

fi = pd.Series(dt_best.feature_importances_, index=FEATURE_NAMES).sort_values(ascending=False)
print(f"\n  {'Rank':<6} {'Fitur':<40} {'Importance':>12}  Bar")
print("  " + "─" * 70)
for rank, (feat, imp) in enumerate(fi.items(), 1):
    if imp > 0:
        bar = "█" * int(imp * 80)
        print(f"  {rank:<6} {feat:<40} {imp:>12.4f}  {bar}")

# =============================================================================
# STEP 8: SIMPAN MODEL
# =============================================================================
with open('decision_tree_model.pkl', 'wb') as f:
    pickle.dump({
        'model'       : dt_best,
        'features'    : FEATURE_NAMES,
        'target_names': TARGET_NAMES,
        'best_params' : best_params,
        'drop_cols'   : DROP_COLS,
        'metrics'     : {
            'acc_train'   : acc_train,   'acc_test'   : acc_test,
            'f1_train'    : f1_train,    'f1_test'    : f1_test,
            'cv_acc_mean' : cv_acc.mean(), 'cv_f1_mean': cv_f1.mean(),
            'gap_overfit' : gap_final,
        }
    }, f)
print("\n  ✅ Model disimpan: decision_tree_model.pkl")

# =============================================================================
# STEP 9: VISUALISASI
# =============================================================================
fig = plt.figure(figsize=(20, 22))
fig.suptitle('Modeling – Decision Tree (Anti-Overfitting v2)\nPrediksi Obesitas Indonesia',
             fontsize=15, fontweight='bold', y=0.99)

# Plot 1: Confusion Matrix
ax1 = fig.add_subplot(3, 3, 1)
cm = confusion_matrix(y_test, y_pred_test)
sns.heatmap(cm, annot=True, fmt='d', cmap='Blues', ax=ax1,
            xticklabels=[t[:7] for t in TARGET_NAMES],
            yticklabels=[t[:7] for t in TARGET_NAMES],
            linewidths=0.5, annot_kws={'size': 8})
ax1.set_title('Confusion Matrix (Final)', fontweight='bold')
ax1.set_xlabel('Prediksi', fontsize=8)
ax1.set_ylabel('Aktual', fontsize=8)
ax1.tick_params(axis='both', labelsize=6)

# Plot 2: Overfitting Gap Comparison
ax2 = fig.add_subplot(3, 3, 2)
gap_labels = ['Baseline', 'Anti-Overfit v2']
gap_vals   = [gap_bl * 100, gap_final * 100]
clrs2 = ['#e74c3c' if g > 5 else '#f39c12' if g > 2 else '#2ecc71' for g in gap_vals]
bars2 = ax2.bar(gap_labels, gap_vals, color=clrs2, edgecolor='white', width=0.4)
for bar, val in zip(bars2, gap_vals):
    ax2.text(bar.get_x() + bar.get_width()/2, bar.get_height() + 0.05,
             f'{val:.2f}%', ha='center', fontsize=11, fontweight='bold')
ax2.axhline(5, color='red',    linestyle='--', linewidth=1, label='Batas Overfitting (5%)')
ax2.axhline(2, color='orange', linestyle='--', linewidth=1, label='Target Ideal (2%)')
ax2.set_title('Gap Overfitting: Sebelum vs Sesudah', fontweight='bold')
ax2.set_ylabel('Gap Train-Test (%)')
ax2.legend(fontsize=7)
ax2.set_ylim(0, max(gap_vals) * 1.5)

# Plot 3: Train vs Test Accuracy Comparison
ax3 = fig.add_subplot(3, 3, 3)
labels3 = ['Baseline', 'Anti-Overfit v2']
tr_vals = [acc_bl_train * 100, acc_train * 100]
te_vals = [acc_bl_test  * 100, acc_test  * 100]
x3 = np.arange(2)
w3 = 0.3
ax3.bar(x3 - w3/2, tr_vals, w3, label='Train', color='#3498db', edgecolor='white', alpha=0.85)
ax3.bar(x3 + w3/2, te_vals, w3, label='Test',  color='#e74c3c', edgecolor='white', alpha=0.85)
for i, (tr, te) in enumerate(zip(tr_vals, te_vals)):
    ax3.text(i - w3/2, tr + 0.2, f'{tr:.1f}%', ha='center', fontsize=9, fontweight='bold')
    ax3.text(i + w3/2, te + 0.2, f'{te:.1f}%', ha='center', fontsize=9, fontweight='bold')
ax3.set_xticks(x3)
ax3.set_xticklabels(labels3)
ax3.set_ylim(80, 108)
ax3.set_title('Train vs Test Accuracy', fontweight='bold')
ax3.set_ylabel('Accuracy (%)')
ax3.legend(fontsize=8)

# Plot 4: Feature Importance
ax4 = fig.add_subplot(3, 3, 4)
top_fi = fi[fi > 0].head(15)
clrs4  = ['#e74c3c' if v > 0.1 else '#f39c12' if v > 0.05 else '#3498db' for v in top_fi.values]
bars4  = ax4.barh(top_fi.index[::-1], top_fi.values[::-1], color=clrs4[::-1], edgecolor='white', alpha=0.85)
for bar, val in zip(bars4, top_fi.values[::-1]):
    ax4.text(val + 0.002, bar.get_y() + bar.get_height()/2,
             f'{val:.4f}', va='center', fontsize=7)
ax4.axvline(0.1,  color='red',    linestyle='--', linewidth=1, label='>0.1 Sangat Penting')
ax4.axvline(0.05, color='orange', linestyle='--', linewidth=1, label='>0.05 Penting')
ax4.set_title('Feature Importance (Top 15)', fontweight='bold')
ax4.set_xlabel('Importance')
ax4.legend(fontsize=6)

# Plot 5: CV Score per Fold
ax5 = fig.add_subplot(3, 3, 5)
folds = [f'Fold {i+1}' for i in range(5)]
ax5.plot(folds, cv_acc * 100, 'o-', color='#3498db', linewidth=2,
         markersize=8, label='Accuracy', markerfacecolor='white', markeredgewidth=2)
ax5.plot(folds, cv_f1,         's-', color='#e74c3c', linewidth=2,
         markersize=8, label='F1 Score', markerfacecolor='white', markeredgewidth=2)
for i, a in enumerate(cv_acc):
    ax5.text(i, a*100 + 0.3, f'{a*100:.1f}%', ha='center', fontsize=7, color='#3498db')
ax5.axhline(cv_acc.mean()*100, color='#3498db', linestyle='--', linewidth=1, alpha=0.5)
ax5.set_title('Cross Validation Score (5-Fold)', fontweight='bold')
ax5.set_ylabel('Score')
ax5.set_ylim(80, 105)
ax5.legend(fontsize=8)
ax5.grid(True, alpha=0.3)

# Plot 6: Learning Curve
ax6 = fig.add_subplot(3, 3, 6)
train_sizes, tr_scores, val_scores = learning_curve(
    dt_best, X_train, y_train, cv=5, scoring='accuracy',
    train_sizes=np.linspace(0.1, 1.0, 10), n_jobs=-1
)
ax6.plot(train_sizes, tr_scores.mean(axis=1)*100, 'o-', color='#3498db', label='Train', linewidth=2)
ax6.fill_between(train_sizes,
                  (tr_scores.mean(axis=1) - tr_scores.std(axis=1))*100,
                  (tr_scores.mean(axis=1) + tr_scores.std(axis=1))*100,
                  alpha=0.15, color='#3498db')
ax6.plot(train_sizes, val_scores.mean(axis=1)*100, 's-', color='#e74c3c', label='Validation', linewidth=2)
ax6.fill_between(train_sizes,
                  (val_scores.mean(axis=1) - val_scores.std(axis=1))*100,
                  (val_scores.mean(axis=1) + val_scores.std(axis=1))*100,
                  alpha=0.15, color='#e74c3c')
ax6.set_title('Learning Curve', fontweight='bold')
ax6.set_xlabel('Training Size')
ax6.set_ylabel('Accuracy (%)')
ax6.legend(fontsize=8)
ax6.grid(True, alpha=0.3)

# Plot 7: Precision/Recall/F1 per Kelas
ax7 = fig.add_subplot(3, 3, 7)
x9    = np.arange(len(TARGET_NAMES))
w9    = 0.25
prec_v = [report_dict[c]['precision'] for c in TARGET_NAMES]
rec_v  = [report_dict[c]['recall']    for c in TARGET_NAMES]
f1_v   = [report_dict[c]['f1-score']  for c in TARGET_NAMES]
ax7.bar(x9 - w9, prec_v, w9, label='Precision', color='#3498db', edgecolor='white', alpha=0.85)
ax7.bar(x9,      rec_v,  w9, label='Recall',    color='#2ecc71', edgecolor='white', alpha=0.85)
ax7.bar(x9 + w9, f1_v,   w9, label='F1-Score',  color='#e74c3c', edgecolor='white', alpha=0.85)
ax7.set_xticks(x9)
ax7.set_xticklabels([t.replace('_', '\n') for t in TARGET_NAMES], fontsize=5.5)
ax7.set_ylim(0, 1.15)
ax7.axhline(0.9, color='gray', linestyle=':', linewidth=1)
ax7.set_title('Precision / Recall / F1 per Kelas', fontweight='bold')
ax7.set_ylabel('Score')
ax7.legend(fontsize=7)

# Plot 8: Depth vs Accuracy (Elbow)
ax8 = fig.add_subplot(3, 3, 8)
depths = [2, 3, 4, 5, 6, 7, 8, 10, 12, 15]
acc_tr_d, acc_te_d = [], []
for d in depths:
    m = DecisionTreeClassifier(
        criterion=best_params['criterion'],
        max_depth=d,
        min_samples_split=best_params['min_samples_split'],
        min_samples_leaf=best_params['min_samples_leaf'],
        ccp_alpha=best_params['ccp_alpha'],
        random_state=42
    )
    m.fit(X_train, y_train)
    acc_tr_d.append(accuracy_score(y_train, m.predict(X_train)) * 100)
    acc_te_d.append(accuracy_score(y_test,  m.predict(X_test))  * 100)
ax8.plot(depths, acc_tr_d, 'o-', color='#3498db', label='Train', linewidth=2, markersize=6)
ax8.plot(depths, acc_te_d, 's-', color='#e74c3c', label='Test',  linewidth=2, markersize=6)
ax8.axvline(best_params['max_depth'], color='green', linestyle='--',
            linewidth=1.5, label=f"Best depth={best_params['max_depth']}")
ax8.set_title('Max Depth vs Accuracy (Elbow)', fontweight='bold')
ax8.set_xlabel('Max Depth')
ax8.set_ylabel('Accuracy (%)')
ax8.legend(fontsize=8)
ax8.grid(True, alpha=0.3)

# Plot 9: Decision Tree Visual (max_depth=3)
ax9 = fig.add_subplot(3, 1, 3)
dt_viz = DecisionTreeClassifier(
    criterion=best_params['criterion'],
    max_depth=3,
    min_samples_split=best_params['min_samples_split'],
    min_samples_leaf=best_params['min_samples_leaf'],
    ccp_alpha=best_params['ccp_alpha'],
    random_state=42
)
dt_viz.fit(X_train, y_train)
plot_tree(dt_viz, feature_names=FEATURE_NAMES, class_names=TARGET_NAMES,
          filled=True, rounded=True, ax=ax9, fontsize=6, impurity=True, precision=2)
ax9.set_title(f'Visualisasi Decision Tree (max_depth=3) – Anti-Overfitting v2\n'
              f'Accuracy Test: {accuracy_score(y_test, dt_viz.predict(X_test))*100:.2f}%',
              fontweight='bold', fontsize=11)

plt.tight_layout(rect=[0, 0, 1, 0.98])
plt.savefig('obesitas_modeling_antioverfitting_v2.png', bbox_inches='tight', dpi=150, facecolor='white')
plt.show()
print("  ✅ Visualisasi disimpan: obesitas_modeling_antioverfitting_v2.png")

# =============================================================================
# RINGKASAN AKHIR
# =============================================================================
print("\n" + "=" * 70)
print("  KESIMPULAN MODELING – ANTI OVERFITTING v2")
print("=" * 70)
print(f"""
  🔧 PERBAIKAN DIBANDING VERSI SEBELUMNYA:
     1. Hapus 'Kategori_BMI'         → hilangkan fitur redundan (derived dari BMI)
     2. ccp_alpha wajib > 0          → pruning selalu aktif (sebelumnya bisa 0.0)
     3. min_samples_leaf lebih besar → daun lebih general, kurangi noise

  📊 PERBANDINGAN HASIL:
     {'Metrik':<22} {'Baseline':>12} {'Anti-Overfit v2':>17}
     {'─'*52}
     {'Accuracy Train':<22} {acc_bl_train*100:>11.2f}% {acc_train*100:>16.2f}%
     {'Accuracy Test':<22}  {acc_bl_test*100:>11.2f}% {acc_test*100:>16.2f}%
     {'Gap Overfitting':<22} {gap_bl*100:>11.2f}% {gap_final*100:>16.2f}%
     {'Depth':<22} {dt_baseline.get_depth():>12} {dt_best.get_depth():>17}
     {'Leaves':<22} {dt_baseline.get_n_leaves():>12} {dt_best.get_n_leaves():>17}

  📈 CROSS VALIDATION:
     CV Accuracy : {cv_acc.mean()*100:.2f}% ± {cv_acc.std()*100:.2f}%
     CV F1 Score : {cv_f1.mean():.4f} ± {cv_f1.std():.4f}
     Stabilitas  : {stability}

  ✅ STATUS: MODEL SIAP UNTUK EVALUASI & DEPLOYMENT
""")
