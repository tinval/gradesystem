import mysql.connector
import csv
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
print(cfg)

# uncomment this to make load database from scratch instead of adding new students
# cursor.execute("DROP TABLE " + cfg['database']['tablename1'])

string_exercises = ', '.join(['Serie'+ str(serie+cfg['exercises1']['start'])+ ' FLOAT DEFAULT NULL' for serie in range(cfg['exercises1']['total'])])
query = "CREATE TABLE IF NOT EXISTS " + cfg['database']['tablename1'] + " (leginr VARCHAR(255) PRIMARY KEY, vorname VARCHAR(255), nachname VARCHAR(255), kurz VARCHAR(255), comment VARCHAR(255) DEFAULT NULL, assistent VARCHAR(255) DEFAULT NULL, " + string_exercises + ")"
cursor.execute(query)

csv_data = csv.reader(open(cfg['edoz_file1']), delimiter='\t')
not_header = False
new_students = 0
total_students = 0
for data in csv_data:
    if not_header:
        total_students += 1
        query='INSERT INTO ' + cfg['database']['tablename1'] + ' (leginr, vorname, nachname, kurz) VALUES ("{}","{}", "{}", "{}");'.format(data[3], data[1], data[0], data[22].split('@')[0])
        try:
            cursor.execute(query)
            print(query)
            new_students += 1
        except mysql.connector.Error as err:
            print(err)
    else:
        not_header = True

print('Added {} to totally {} students'.format(new_students, total_students))
db.commit()
cursor.close()
db.close()
