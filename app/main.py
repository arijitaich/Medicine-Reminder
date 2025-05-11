from flask import Flask, request, jsonify
from datetime import datetime
from app.schedule_loader import get_medicines  # Adjusted import path

app = Flask(__name__)

@app.route('/get-medicines', methods=['GET'])
def get_meds():
    day = request.args.get("day", datetime.today().strftime('%A'))
    time_slot = request.args.get("time")

    if not time_slot:
        return jsonify({"error": "Missing 'time' parameter"}), 400

    meds = get_medicines(day, time_slot)

    if meds:
        return jsonify({
            "day": day,
            "time": time_slot,
            "medicines": meds
        })
    else:
        return jsonify({"message": f"No medicines scheduled for {day} {time_slot}."}), 200

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5500)  # Updated to run on 0.0.0.0:5500
