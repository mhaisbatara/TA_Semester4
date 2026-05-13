from flask import Flask, request, jsonify
from flask_cors import CORS
import pickle
import numpy as np

app = Flask(__name__)
CORS(app)

# Load model
with open('decision_tree_model.pkl', 'rb') as f:
    data = pickle.load(f)

model        = data['model']
target_names = data['target_names']

@app.route('/health', methods=['GET'])
def health():
    return jsonify({'status': 'Flask ML running!'})

@app.route('/predict', methods=['POST'])
def predict():
    d = request.json

    print("=== DATA DITERIMA PYTHON ===")
    print(d)

    # Hitung BMI
    tinggi = d['tinggi']
    berat  = d['berat']
    bmi    = berat / (tinggi ** 2)

    if bmi < 18.5:   kategori_bmi = 0
    elif bmi < 25.0: kategori_bmi = 1
    elif bmi < 30.0: kategori_bmi = 2
    else:            kategori_bmi = 3

    # Encode — key sudah snake_case semua ✅
    alkohol_map = {"Tidak": 0, "Kadang": 1, "Sering": 2, "Selalu": 3}
    ngamil_map  = {"Tidak": 0, "Kadang": 1, "Sering": 2, "Selalu": 3}
    transport   = d['transportasi']        # ✅ fix: 'transport' → 'transportasi'

    input_data = np.array([[
        d['usia'],
        tinggi,
        berat,
        bmi,
        kategori_bmi,
        d['konsumsi_sayur'],               # ✅ fix: 'sayur' → 'konsumsi_sayur'
        d['makan_harian'],
        d['konsumsi_air'],
        d['aktivitas_fisik'],              # ✅ fix: 'aktivitas' → 'aktivitas_fisik'
        d['waktu_layar'],
        1 if d['jenis_kelamin'] == "Laki-laki" else 0,
        alkohol_map[d['alkohol']],
        1 if d['kalori_tinggi'] == "Ya" else 0,
        1 if d['monitoring'] == "Ya" else 0,
        1 if d['merokok'] == "Ya" else 0,
        1 if d['riwayat_keluarga'] == "Ya" else 0,  # ✅ fix: 'riwayat' → 'riwayat_keluarga'
        ngamil_map[d['ngemil']],           # ✅ fix: 'ngamil' → 'ngemil'
        1 if transport == "Jalan_Kaki" else 0,
        1 if transport == "Mobil" else 0,
        1 if transport == "Motor" else 0,
        1 if transport == "Sepeda" else 0,
        1 if transport == "Transportasi_Umum" else 0,
    ]])

    hasil_idx  = model.predict(input_data)[0]
    hasil      = target_names[hasil_idx]
    proba      = model.predict_proba(input_data)[0]
    confidence = round(float(max(proba)) * 100, 2)

    return jsonify({
        'data': {                          # ✅ tambah wrapper 'data' agar Flutter bisa .['data']
            'kategori':   hasil,
            'bmi':        round(bmi, 2),
            'confidence': confidence,
        }
    })

if __name__ == '__main__':
    app.run(debug=True, port=5000)