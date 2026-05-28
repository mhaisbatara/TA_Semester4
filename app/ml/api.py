"""
=============================================================================
API PREDIKSI OBESITAS - Flask (v2)
Model: Decision Tree (Anti-Overfitting v2)
Fix: hapus Kategori_BMI dari fitur input (redundan dengan BMI)
Fix: tinggi sudah dalam METER dari Flutter — tidak perlu dibagi 100
=============================================================================
"""

from flask import Flask, request, jsonify
from flask_cors import CORS
import pickle
import numpy as np

app = Flask(__name__)
CORS(app)

# =============================================================================
# LOAD MODEL
# =============================================================================
with open('decision_tree_model.pkl', 'rb') as f:
    data = pickle.load(f)

model         = data['model']
target_names  = data['target_names']
feature_names = data['features']   # urutan fitur saat training (tanpa Kategori_BMI)

print("=" * 60)
print("  ✅ Model loaded!")
print(f"  Fitur ({len(feature_names)}):")
for i, f in enumerate(feature_names):
    print(f"    {i+1:>2}. {f}")
print("=" * 60)

# =============================================================================
# HELPER: ENCODE INPUT → ARRAY
# =============================================================================
def build_input(d):
    """
    Konversi data JSON dari Flutter ke numpy array.
    Urutan fitur harus SAMA PERSIS dengan saat training.

    Fitur (21) — Kategori_BMI DIHAPUS karena redundan dengan BMI:
     0  Usia
     1  Tinggi_Badan              ← dalam METER (Flutter kirim langsung meter)
     2  Berat_Badan
     3  BMI                       ← dihitung di sini
     4  Frekuensi_Konsumsi_Sayur
     5  Jumlah_Makan_Harian
     6  Konsumsi_Air
     7  Aktivitas_Fisik
     8  Waktu_Layar
     9  Jenis_Kelamin             ← 1=Laki-laki, 0=Perempuan
    10  Konsumsi_Alkohol          ← 0=Tidak,1=Kadang,2=Sering,3=Selalu
    11  Sering_Makan_Tinggi_Kalori← 1=Ya, 0=Tidak
    12  Monitoring_Kalori         ← 1=Ya, 0=Tidak
    13  Merokok                   ← 1=Ya, 0=Tidak
    14  Riwayat_Keluarga_Overweight← 1=Ya, 0=Tidak
    15  Kebiasaan_Ngamil          ← 0=Tidak,1=Kadang,2=Sering,3=Selalu
    16  Transportasi_Jalan_Kaki   ← OHE
    17  Transportasi_Mobil        ← OHE
    18  Transportasi_Motor        ← OHE
    19  Transportasi_Sepeda       ← OHE
    20  Transportasi_Transportasi_Umum ← OHE
    """

    # ── Flutter mengirim tinggi dalam METER (contoh: 1.70) ──────────────────
    # TIDAK perlu dibagi 100 — jika dibagi 100 → BMI jadi ratusan ribu!
    tinggi_m = float(d['tinggi'])   # ✅ FIX: langsung pakai, sudah dalam meter
    berat    = float(d['berat'])

    # ── Hitung BMI ──────────────────────────────────────────────────────────
    bmi = berat / (tinggi_m ** 2)

    # ── Kategori BMI hanya untuk response label, TIDAK masuk ke model ───────
    if   bmi < 18.5: kategori_bmi = 0   # Kurus
    elif bmi < 25.0: kategori_bmi = 1   # Normal
    elif bmi < 30.0: kategori_bmi = 2   # Overweight
    else:            kategori_bmi = 3   # Obesitas

    # ── Encoding map ─────────────────────────────────────────────────────────
    frekuensi_map = {"Tidak": 0, "Kadang": 1, "Sering": 2, "Selalu": 3}
    transport     = d.get('transportasi', '')

    # ── Susun array sesuai urutan training (21 fitur, tanpa Kategori_BMI) ───
    row = [
        float(d['usia']),                                        #  0 Usia
        tinggi_m,                                                #  1 Tinggi_Badan (meter)
        berat,                                                   #  2 Berat_Badan
        round(bmi, 4),                                          #  3 BMI
        float(d.get('konsumsi_sayur', 2)),                      #  4 Frekuensi_Konsumsi_Sayur
        float(d.get('makan_harian', 3)),                        #  5 Jumlah_Makan_Harian
        float(d.get('konsumsi_air', 2)),                        #  6 Konsumsi_Air
        float(d.get('aktivitas_fisik', 1)),                     #  7 Aktivitas_Fisik
        float(d.get('waktu_layar', 2)),                         #  8 Waktu_Layar
        1.0 if d.get('jenis_kelamin') == "Laki-laki" else 0.0, #  9 Jenis_Kelamin
        float(frekuensi_map.get(d.get('alkohol', 'Tidak'), 0)),# 10 Konsumsi_Alkohol
        1.0 if d.get('kalori_tinggi') == "Ya" else 0.0,        # 11 Sering_Makan_Tinggi_Kalori
        1.0 if d.get('monitoring') == "Ya" else 0.0,           # 12 Monitoring_Kalori
        1.0 if d.get('merokok') == "Ya" else 0.0,              # 13 Merokok
        1.0 if d.get('riwayat_keluarga') == "Ya" else 0.0,     # 14 Riwayat_Keluarga_Overweight
        float(frekuensi_map.get(d.get('ngemil', 'Tidak'), 0)), # 15 Kebiasaan_Ngamil
        1.0 if transport == "Jalan_Kaki" else 0.0,             # 16 Transportasi_Jalan_Kaki
        1.0 if transport == "Mobil" else 0.0,                  # 17 Transportasi_Mobil
        1.0 if transport == "Motor" else 0.0,                  # 18 Transportasi_Motor
        1.0 if transport == "Sepeda" else 0.0,                 # 19 Transportasi_Sepeda
        1.0 if transport == "Transportasi_Umum" else 0.0,      # 20 Transportasi_Transportasi_Umum
    ]

    return np.array([row]), bmi, kategori_bmi


# =============================================================================
# ROUTE: HEALTH CHECK
# =============================================================================
@app.route('/health', methods=['GET'])
def health():
    return jsonify({
        'status' : 'Flask ML running! (v2 – Anti Overfitting)',
        'model'  : 'Decision Tree (Anti-Overfitting v2)',
        'fitur'  : len(feature_names),
        'kelas'  : len(target_names),
    })


# =============================================================================
# ROUTE: PREDICT
# =============================================================================
@app.route('/predict', methods=['POST'])
def predict():
    try:
        d = request.json

        if d is None:
            return jsonify({'error': 'Body JSON kosong'}), 400

        print("\n=== DATA DITERIMA ===")
        print(d)

        # Validasi field wajib
        required = ['usia', 'tinggi', 'berat', 'jenis_kelamin',
                    'aktivitas_fisik', 'transportasi']
        missing = [k for k in required if k not in d]
        if missing:
            return jsonify({'error': f'Field kurang: {missing}'}), 400

        # Build input array
        input_array, bmi, kategori_bmi = build_input(d)

        print(f"\n  Tinggi     : {d['tinggi']} m (langsung dari Flutter)")
        print(f"  Berat      : {d['berat']} kg")
        print(f"  BMI        : {bmi:.2f}")
        print(f"  Input shape: {input_array.shape}")
        print(f"  Input array: {input_array}")

        # Prediksi
        hasil_idx  = model.predict(input_array)[0]
        hasil      = target_names[hasil_idx]
        proba      = model.predict_proba(input_array)[0]
        confidence = round(float(max(proba)) * 100, 2)

        kat_bmi_label = {0: "Kurus", 1: "Normal", 2: "Overweight", 3: "Obesitas"}

        print(f"\n  ✅ Prediksi : {hasil}")
        print(f"  Confidence : {confidence}%")
        print(f"  Probabilitas per kelas:")
        for name, prob in zip(target_names, proba):
            print(f"    {name:<35}: {prob*100:.2f}%")

        return jsonify({
            'data': {
                'kategori'     : hasil,
                'bmi'          : round(bmi, 2),
                'kategori_bmi' : kat_bmi_label[kategori_bmi],
                'confidence'   : confidence,
                'probabilitas' : {
                    name: round(float(prob) * 100, 2)
                    for name, prob in zip(target_names, proba)
                }
            }
        })

    except KeyError as e:
        print(f"  ❌ KeyError: {e}")
        return jsonify({'error': f'Field tidak ditemukan: {str(e)}'}), 400

    except Exception as e:
        print(f"  ❌ Error: {e}")
        return jsonify({'error': str(e)}), 500


# =============================================================================
# ROUTE: CEK FITUR (debugging)
# =============================================================================
@app.route('/features', methods=['GET'])
def features():
    return jsonify({
        'total'  : len(feature_names),
        'fitur'  : {i: name for i, name in enumerate(feature_names)},
        'kelas'  : {i: name for i, name in enumerate(target_names)},
    })


# =============================================================================
# MAIN
# =============================================================================
if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
