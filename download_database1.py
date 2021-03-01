import mysql.connector
import pandas as pd
import time
import json

with open('config.json', 'r') as f:
    cfg = json.load(f)
    
db = mysql.connector.connect(
  host=cfg['database']['host'],
  user=cfg['database']['user'],
  passwd=cfg['database']['passwd'],
  database=cfg['database']['database']
)

cursor = db.cursor()
df = pd.read_sql('select * from ' + cfg['database']['tablename1'],db)

print(df)

try:
	filename2 = cfg['path_results'] + 'results_1_' + time.strftime("%Y%m%d-%H%M%S") + '.csv'
	df.to_csv(filename2, index=False)
except error as e:
	print(e)
cursor.close()
db.close()
