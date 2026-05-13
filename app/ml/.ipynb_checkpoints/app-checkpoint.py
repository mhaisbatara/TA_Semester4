import streamlit as st
import pickle
import numpy as np
import pandas as pd

# ========================
# Load Model
# ========================
with open('decision_tree_model.pkl', 'rb') as f:
    data = pickle.load(f)

model         = data['model']
feature_names = data['features']
target_names  = data['target_names']

# ========================
# Config
# ========================
st.set_page_config(page_title="Prediksi Obesitas", page_icon="⚖️", layout="centered")
st.title("⚖️ Prediksi Kategori Obesitas")
st.markdown("Isi data di bawah ini untuk memprediksi kategori obesitas.")

# ========================
# Form Input
# ========================
with st.form("form_prediksi"):
    st.subheader("📋 Data Fisik")
    col1, col2 = st.columns(2)

    with col1:
        usia   = st.number_input(
            "Usia (tahun)",
            min_value=14, max_value=61, value=22,
            step=1
        )
        tinggi = st.number_input(
            "Tinggi Badan (meter)",
            min_value=1.45, max_value=1.98, value=1.70,
            step=0.01, format="%.2f"
        )
        berat  = st.number_input(
            "Berat Badan (kg)",
            min_value=39.0, max_value=173.0, value=65.0,
            step=0.5, format="%.1f"
        )
        sayur  = st.number_input(
            "Frekuensi Konsumsi Sayur (porsi/hari)",
            min_value=1.0, max_value=3.0, value=2.0,
            step=0.5, format="%.1f"
        )

    with col2:
        makan_harian = st.number_input(
            "Jumlah Makan Harian",
            min_value=1, max_value=4, value=3,
            step=1
        )
        konsumsi_air = st.number_input(
            "Konsumsi Air (liter/hari)",
            min_value=1.0, max_value=3.0, value=2.0,
            step=0.5, format="%.1f"
        )
        aktivitas = st.number_input(
            "Aktivitas Fisik (jam/hari)",
            min_value=0.0, max_value=3.0, value=1.0,
            step=0.5, format="%.1f"
        )
        waktu_layar = st.number_input(
            "Waktu Layar (jam/hari)",
            min_value=0.0, max_value=2.0, value=1.0,
            step=0.5, format="%.1f"
        )

    st.subheader("🏃 Gaya Hidup")
    col3, col4 = st.columns(2)

    with col3:
        jenis_kelamin = st.selectbox("Jenis Kelamin",
            ["Perempuan", "Laki-laki"])
        alkohol       = st.selectbox("Konsumsi Alkohol",
            ["Tidak", "Kadang", "Sering", "Selalu"])
        kalori_tinggi = st.selectbox("Sering Makan Tinggi Kalori",
            ["Tidak", "Ya"])
        monitoring    = st.selectbox("Monitoring Kalori",
            ["Tidak", "Ya"])

    with col4:
        merokok  = st.selectbox("Merokok",
            ["Tidak", "Ya"])
        riwayat  = st.selectbox("Riwayat Keluarga Overweight",
            ["Ya", "Tidak"])
        ngamil   = st.selectbox("Kebiasaan Ngemil",
            ["Kadang", "Sering", "Selalu", "Tidak"])
        transport = st.selectbox("Transportasi",
            ["Transportasi_Umum", "Jalan_Kaki", "Mobil", "Motor", "Sepeda"])

    submitted = st.form_submit_button("🔍 Prediksi Sekarang", use_container_width=True)

# ========================
# Prediksi
# ========================
if submitted:

    # Hitung BMI
    bmi = berat / (tinggi ** 2)

    # Kategori BMI
    if bmi < 18.5:
        kategori_bmi = 0
    elif bmi < 25.0:
        kategori_bmi = 1
    elif bmi < 30.0:
        kategori_bmi = 2
    else:
        kategori_bmi = 3

    # Encode kategorik
    gender_enc  = 1 if jenis_kelamin == "Laki-laki" else 0
    alkohol_map = {"Tidak": 0, "Kadang": 1, "Sering": 2, "Selalu": 3}
    alkohol_enc = alkohol_map[alkohol]
    kalori_enc  = 1 if kalori_tinggi == "Ya" else 0
    monitor_enc = 1 if monitoring == "Ya" else 0
    merokok_enc = 1 if merokok == "Ya" else 0
    riwayat_enc = 1 if riwayat == "Ya" else 0
    ngamil_map  = {"Tidak": 0, "Kadang": 1, "Sering": 2, "Selalu": 3}
    ngamil_enc  = ngamil_map[ngamil]

    # One-hot encoding Transportasi
    transport_jalan  = 1 if transport == "Jalan_Kaki" else 0
    transport_mobil  = 1 if transport == "Mobil" else 0
    transport_motor  = 1 if transport == "Motor" else 0
    transport_sepeda = 1 if transport == "Sepeda" else 0
    transport_umum   = 1 if transport == "Transportasi_Umum" else 0

    # Susun fitur sesuai urutan training:
    # ['Usia', 'Tinggi_Badan', 'Berat_Badan', 'BMI', 'Kategori_BMI',
    #  'Frekuensi_Konsumsi_Sayur', 'Jumlah_Makan_Harian', 'Konsumsi_Air',
    #  'Aktivitas_Fisik', 'Waktu_Layar', 'Jenis_Kelamin', 'Konsumsi_Alkohol',
    #  'Sering_Makan_Tinggi_Kalori', 'Monitoring_Kalori', 'Merokok',
    #  'Riwayat_Keluarga_Overweight', 'Kebiasaan_Ngamil',
    #  'Transportasi_Jalan_Kaki', 'Transportasi_Mobil', 'Transportasi_Motor',
    #  'Transportasi_Sepeda', 'Transportasi_Transportasi_Umum']
    input_data = np.array([[
        usia,
        tinggi,
        berat,
        bmi,
        kategori_bmi,
        sayur,
        makan_harian,
        konsumsi_air,
        aktivitas,
        waktu_layar,
        gender_enc,
        alkohol_enc,
        kalori_enc,
        monitor_enc,
        merokok_enc,
        riwayat_enc,
        ngamil_enc,
        transport_jalan,
        transport_mobil,
        transport_motor,
        transport_sepeda,
        transport_umum,
    ]])

    # Prediksi
    hasil_idx  = model.predict(input_data)[0]
    hasil      = target_names[hasil_idx]
    proba      = model.predict_proba(input_data)[0]
    confidence = max(proba) * 100

    # Icon
    color_map = {
        "Kurus":                "🔵",
        "Normal":               "🟢",
        "Overweight_Tingkat_1": "🟡",
        "Overweight_Tingkat_2": "🟠",
        "Obesitas_Tipe_1":      "🔴",
        "Obesitas_Tipe_2":      "🔴",
        "Obesitas_Tipe_3":      "🔴",
    }
    icon = color_map.get(hasil, "⚪")

    st.divider()
    st.subheader("📊 Hasil Prediksi")

    col_a, col_b, col_c = st.columns(3)
    col_a.metric("Kategori",   f"{icon} {hasil.replace('_', ' ')}")
    col_b.metric("BMI",        f"{bmi:.2f} kg/m²")
    col_c.metric("Confidence", f"{confidence:.1f}%")

    # Rekomendasi
    st.divider()
    st.subheader("💡 Rekomendasi")
    rekomendasi = {
        "Kurus":                "Tingkatkan asupan kalori bergizi. Konsultasi dengan ahli gizi untuk program penambahan berat badan yang sehat.",
        "Normal":               "Pertahankan pola makan sehat dan aktivitas fisik rutin. Anda berada di kondisi ideal!",
        "Overweight_Tingkat_1": "Kurangi makanan tinggi kalori, tingkatkan aktivitas fisik minimal 30 menit/hari.",
        "Overweight_Tingkat_2": "Konsultasi dokter. Perlu diet ketat, hindari makanan berlemak, dan olahraga teratur.",
        "Obesitas_Tipe_1":      "Segera konsultasi dokter. Diet rendah kalori dan olahraga rutin sangat dianjurkan.",
        "Obesitas_Tipe_2":      "Konsultasi dokter spesialis. Mungkin perlu penanganan medis dan perubahan gaya hidup drastis.",
        "Obesitas_Tipe_3":      "Segera tangani dengan dokter spesialis. Risiko kesehatan sangat tinggi — perlu intervensi medis segera.",
    }
    st.info(rekomendasi.get(hasil, "Konsultasikan dengan tenaga medis."))

    # Probabilitas semua kelas
    st.divider()
    st.subheader("📈 Probabilitas Semua Kategori")
    prob_df = pd.DataFrame({
        "Kategori":         target_names,
        "Probabilitas (%)": [round(p * 100, 2) for p in proba]
    }).sort_values("Probabilitas (%)", ascending=False)
    st.dataframe(prob_df, use_container_width=True, hide_index=True)

    st.divider()
    for cat, prob in zip(target_names, proba):
        st.write(f"**{cat.replace('_', ' ')}**")
        st.progress(float(prob))