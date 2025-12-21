import os
import shutil

codes = [
    '0200', '0201', '0202', '0203', '0204', '0205', '0206', '0207', '0208', '0209',
    '0300', '0301', '0302', '0303',
    '0400', '0401', '0403', '0404', '0405', '0406', '0409',
    '0500', '0501', '0502', '0503',
    '0600', '0601', '0602', '0603',
    '0700', '0701', '0703', '0711', '0713',
    '0900', '0901', '0902', '0904', '0905'
]

source = r"C:\Users\HP\.gemini\antigravity\brain\114b6487-1e3d-4984-acbe-3cb6dab3552d\fefco_generic_placeholder_1766306681209.png"
dest_dir = r"c:\xampp\htdocs\qcproduction\public\images\fefco"

if not os.path.exists(dest_dir):
    os.makedirs(dest_dir)

print(f"Checking {len(codes)} codes...")

for code in codes:
    target = os.path.join(dest_dir, f"{code}.png")
    if not os.path.exists(target):
        try:
            shutil.copy2(source, target)
            print(f"Created {code}.png")
        except Exception as e:
            print(f"Error creating {code}.png: {e}")
    else:
        print(f"Exists {code}.png")

print("Done.")
