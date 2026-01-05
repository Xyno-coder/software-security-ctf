from flask import Flask, request, send_file, jsonify
from flask_cors import CORS
import os
import jwt

app = Flask(__name__)
CORS(app, resources={r"/api/*": {"origins": "*"}}, supports_credentials=True)

@app.route('/')
def index():
    return jsonify({
        "message": "Bienvenue sur le mini-CTF ! Essayez de trouver le fichier secret 😉",
        "hint": "peut-être un fichier .env quelque part..."
    })

@app.route('/api/download')
def api_download():
    file = request.args.get('file', 'index.html')
    path = os.path.join(os.getcwd(), 'files', file)
    try:
        return send_file(path)
    except Exception as e:
        return jsonify({"error": str(e)}), 404

@app.route('/api/admin')
def api_admin():
	auth = request.headers.get("Authorization", "")
	if "Bearer" not in auth:
		return jsonify({"error": "Missing token"}), 401

	token = auth.split(" ")[1]
	secret = os.getenv("JWT_SECRET", "super_secret_key")

	try:
		decoded = jwt.decode(token, secret, algorithms=["HS256"])
		if decoded.get("role") == "admin":
			return jsonify({"flag": os.getenv("FLAG", "FLAG{What_one_man_calls_God,_another_calls_the_laws_of_physics}")})
		else:
			return jsonify({"error": "You are not admin"})
	except Exception as e:
		return jsonify({"error": str(e)}), 403

if __name__ == '__main__':
    os.makedirs('files', exist_ok=True)
    with open('files/README.txt', 'w') as f:
        f.write("Bienvenue sur le challenge ! Essayez /download?file=README.txt 😉")
    app.run(host='0.0.0.0', port=80)
def api_admin():
	auth = request.headers.get("Authorization", "")
	if "Bearer" not in auth:
		return jsonify({"error": "Missing token"}), 401

	token = auth.split(" ")[1]
	secret = os.getenv("JWT_SECRET", "devsecret")

	try:
		jwt = _load_pyjwt()
	except RuntimeError as e:
		# Problème de librairie JWT (non installé ou mauvais package)
		return jsonify({"error": str(e)}), 500

	try:
		decoded = jwt.decode(token, secret, algorithms=["HS256"])
		if decoded.get("role") == "admin":
			return jsonify({"flag": os.getenv("FLAG", "FLAG{fake_flag}")})
		else:
			return jsonify({"error": "You are not admin"})
	except Exception as e:
		return jsonify({"error": str(e)}), 403

if __name__ == '__main__':
    os.makedirs('files', exist_ok=True)
    with open('files/README.txt', 'w') as f:
        f.write("Bienvenue sur le challenge ! Essayez /download?file=README.txt 😉")
    app.run(host='0.0.0.0', port=80)
