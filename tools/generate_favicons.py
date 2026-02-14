from PIL import Image
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
IMG_DIR = ROOT / 'img'
SOURCE = IMG_DIR / 'favicon.png'

if not SOURCE.exists():
    raise SystemExit(f"Source favicon not found at {SOURCE}")

sizes = {
    'favicon-16x16.png': (16,16),
    'favicon-32x32.png': (32,32),
    'apple-touch-icon.png': (180,180),
}

img = Image.open(SOURCE).convert('RGBA')
for name, size in sizes.items():
    out = IMG_DIR / name
    resized = img.resize(size, Image.LANCZOS)
    resized.save(out)
    print('Wrote', out)

# Create favicon.ico containing 16x16 and 32x32
ico_out = IMG_DIR / 'favicon.ico'
img_sizes = [(16,16),(32,32),(48,48)]
ico_imgs = [img.resize(s, Image.LANCZOS) for s in img_sizes]
ico_imgs[0].save(ico_out, format='ICO', sizes=img_sizes)
print('Wrote', ico_out)
