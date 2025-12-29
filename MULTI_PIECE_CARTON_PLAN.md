# Multi-Piece Carton Support - Implementation Plan

## Overview
When a carton has multiple pieces (e.g., 2-piece carton with lid and base), each piece needs separate specifications for:
- Reel Deckle
- Sheet Length
- Ply Type
- Paper Material (layers)
- Die Line Sketch

## Database Changes Required

### 1. Add `pieces_count` to job_cards table
```sql
ALTER TABLE job_cards ADD COLUMN pieces_count INTEGER DEFAULT 1;
```

### 2. Create `job_card_pieces` table
```sql
CREATE TABLE job_card_pieces (
    id BIGINT PRIMARY KEY,
    job_card_id BIGINT FOREIGN KEY,
    piece_number INTEGER,
    piece_name VARCHAR(100), -- e.g., "Lid", "Base", "Piece 1"
    deckle_size DECIMAL(10,2),
    sheet_length DECIMAL(10,2),
    ply_type INTEGER,
    die_sketch_path VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### 3. Update `job_card_layers` table
Add foreign key to piece:
```sql
ALTER TABLE job_card_layers ADD COLUMN piece_id BIGINT NULL;
ALTER TABLE job_card_layers ADD FOREIGN KEY (piece_id) REFERENCES job_card_pieces(id);
```

## UI Changes Required

### Job Card Create/Edit Form
1. Add "Number of Pieces" dropdown (1, 2, 3)
2. When pieces > 1:
   - Show tabs for each piece (Piece 1, Piece 2, etc.)
   - Each tab contains:
     * Piece Name input
     * Deckle Size
     * Sheet Length
     * Ply Type
     * Paper Layers (repeatable)
     * Die Sketch Upload

### Job Card Print View
- Show specifications for each piece separately
- Display die sketches for each piece

## Implementation Steps

### Phase 1: Database Migration
1. Create migration for pieces_count column
2. Create migration for job_card_pieces table
3. Create migration to add piece_id to job_card_layers
4. Run migrations

### Phase 2: Model Updates
1. Update JobCard model with pieces relationship
2. Create JobCardPiece model
3. Update JobCardLayer model with piece relationship

### Phase 3: Controller Updates
1. Update JobCardController store method
2. Update JobCardController update method
3. Handle piece data in validation and storage

### Phase 4: View Updates
1. Update create.blade.php with dynamic piece forms
2. Update edit.blade.php with piece tabs
3. Update print.blade.php to show all pieces
4. Add JavaScript for dynamic piece management

### Phase 5: File Handling
1. Add die sketch upload for each piece
2. Store sketches with piece identifier
3. Display sketches in print view

## Example Data Structure

### Single Piece Carton (Current)
```json
{
  "job_card": {
    "pieces_count": 1,
    "deckle_size": 48,
    "sheet_length": 36,
    "ply_type": 3
  }
}
```

### Two Piece Carton (New)
```json
{
  "job_card": {
    "pieces_count": 2,
    "pieces": [
      {
        "piece_number": 1,
        "piece_name": "Lid",
        "deckle_size": 48,
        "sheet_length": 36,
        "ply_type": 3,
        "layers": [...]
      },
      {
        "piece_number": 2,
        "piece_name": "Base",
        "deckle_size": 52,
        "sheet_length": 40,
        "ply_type": 5,
        "layers": [...]
      }
    ]
  }
}
```

## Notes
- Backward compatibility: Existing single-piece cartons work as-is
- Default pieces_count = 1 for existing records
- When pieces_count = 1, use main job_card fields
- When pieces_count > 1, use job_card_pieces data

## Estimated Effort
- Database: 2 hours
- Models: 1 hour
- Controllers: 3 hours
- Views: 4 hours
- Testing: 2 hours
**Total: ~12 hours**
