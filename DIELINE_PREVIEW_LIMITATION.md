# Die-Line Preview Limitation

## Current Status

The die-line preview feature currently generates only **Regular Slotted Container (RSC - 0201)** style die-lines, regardless of the selected carton type.

## Issue

While the FEFCO code is correctly extracted from the carton type selection (e.g., "0204" for Overlap Slotted Container), the `DieLine.php` service does not use this code to generate different die-line designs.

## Root Cause

The `DieLine::generateSVG()` method in `app/Services/DieLine.php`:
- Accepts `$fefcoCode` as a parameter (line 17)
- But only implements one die-line pattern (RSC/0201 style)
- Does not have logic to generate different patterns for different FEFCO codes

## Current Implementation

```php
public function generateSVG($length, $width, $height, $fefcoCode = '0201', $itemName = '')
{
    // $fefcoCode is received but not used
    // Only generates RSC (0201) pattern
    // Same die-line for all carton types
}
```

## Solutions

### Option 1: Use External Die-Line Images (Recommended)
Instead of generating SVG, display pre-made die-line images for each FEFCO code:

1. Store die-line images in `public/images/fefco/`
2. Naming: `0201.png`, `0204.png`, etc.
3. Display image based on selected FEFCO code

**Pros:**
- Accurate representations
- Quick implementation
- Professional quality
- No complex SVG generation

**Cons:**
- Static images (not customized to dimensions)
- Need images for each FEFCO code

### Option 2: Implement Multiple SVG Generators
Create different SVG generation logic for each FEFCO code:

```php
public function generateSVG($length, $width, $height, $fefcoCode = '0201', $itemName = '')
{
    switch($fefcoCode) {
        case '0201':
            return $this->generate0201($length, $width, $height, $itemName);
        case '0204':
            return $this->generate0204($length, $width, $height, $itemName);
        case '0427':
            return $this->generate0427($length, $width, $height, $itemName);
        // ... etc
        default:
            return $this->generate0201($length, $width, $height, $itemName);
    }
}
```

**Pros:**
- Dynamic, dimension-based
- Customized to exact size
- Professional

**Cons:**
- Very time-consuming
- Complex geometry for each type
- Requires FEFCO specification knowledge

### Option 3: Hybrid Approach
- Use static images for preview
- Generate custom SVG only for common types (0201, 0204, 0427)
- Fall back to generic image for others

## Recommendation

**Use Option 1 (External Images)** because:
1. Quick to implement (5 minutes)
2. Accurate representations
3. Professional quality
4. Covers all FEFCO codes

The die-line preview is mainly for reference. The actual die-line creation happens in production with proper die-cutting tools.

## Implementation (Option 1)

### Step 1: Get FEFCO Images
Download or create die-line images for common FEFCO codes:
- 0201.png (RSC)
- 0204.png (Overlap Slotted)
- 0427.png (Die-Cut)
- etc.

### Step 2: Store Images
```
public/images/fefco/
├── 0201.png
├── 0204.png
├── 0427.png
└── ...
```

### Step 3: Update Preview Logic
```javascript
function generateDieLine() {
    // ... existing code ...
    
    // Instead of generating SVG, show image
    const imageUrl = `/images/fefco/${fefcoCode}.png`;
    document.getElementById('dieline-container').innerHTML = 
        `<img src="${imageUrl}" alt="FEFCO ${fefcoCode}" 
              style="max-width:100%; height:auto;"
              onerror="this.src='/images/fefco/0201.png'">`;
}
```

## Current Workaround

For now, the preview shows RSC (0201) style for all carton types. This gives a general idea of the die-line layout, but the actual pattern may differ based on the FEFCO code.

Users should refer to:
1. FEFCO code in carton type name
2. Standard FEFCO documentation
3. Physical die-cutting tools for actual production

## Status

- ✅ FEFCO code extraction: **WORKING**
- ✅ Code passed to backend: **WORKING**
- ❌ Different die-lines per code: **NOT IMPLEMENTED**
- 📋 Recommendation: **Use static FEFCO images**

---

**Note:** The die-line preview is a reference tool. Actual die-cutting in production uses proper tooling and specifications, not this preview.
