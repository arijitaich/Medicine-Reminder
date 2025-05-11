import json
import os

SCHEDULE_FILE = os.path.join(os.path.dirname(__file__), "med_schedule.json")

def load_schedule():
    with open(SCHEDULE_FILE, "r") as f:
        return json.load(f)

def get_medicines(day, time_slot):
    schedule = load_schedule()
    return schedule.get(day, {}).get(time_slot, [])
