<?php

namespace App\Services;

class DieLine
{
    /**
     * Generate SVG die-line for a corrugated carton box
     *
     * @param float $length Length in mm
     * @param float $width Width in mm
     * @param float $height Height in mm
     * @param string $fefcoCode FEFCO standard code
     * @param string $itemName Item Name string
     * @return string SVG markup
     */
    public function generateSVG($length, $width, $height, $fefcoCode = '0201', $itemName = '')
    {
        // Constants
        $glueFlap = 35; // Fixed glue flap width in mm

        // Upper flap size should match lower flap size as per requirement
        $flap2 = 0.5 * $width; // Near side flap
        $flap1 = $flap2; // Upper flap size equal to lower flap size

        // Total dimensions
        $totalWidth  = $glueFlap + (2 * $length) + (2 * $width);
        $totalHeight = $flap1 + $height + $flap2;

        // Scaling to fit ~800px width
        $scale = 800 / $totalWidth;
        $viewBoxWidth  = $totalWidth * $scale;
        
        // Gap requirement: 50mm above dimension arrow
        $textGapMM = 50;
        $arrowOffset = 20; // Arrow is 20 units above drawing
        
        // Calculate required Title Height to fit: ArrowOffset + Gap + FontHeight buffer
        $requiredTitleSpace = $arrowOffset + ($textGapMM * $scale) + 30; // 30px buffer for text itself
        $titleHeight = max(100, $requiredTitleSpace); // Minimum 100 or required
        
        $viewBoxHeight = ($totalHeight * $scale) + $titleHeight;

        // Padding for labels and arrows
        $padding = 60;
        $canvasWidth  = $viewBoxWidth + (2 * $padding);
        $canvasHeight = $viewBoxHeight + (2 * $padding);

        // Starting point (with padding)
        $startX = $padding;
        $startY = $padding + ($flap1 * $scale) + $titleHeight; // Shift down by titleHeight

        // Begin SVG
        $svg = '<?xml version="1.0" encoding="UTF-8"?>';
        $svg .= sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %d %d" width="%d" height="%d">',
            $canvasWidth,
            $canvasHeight,
            $canvasWidth,
            $canvasHeight
        );
        // Background
        $svg .= sprintf('<rect width="%d" height="%d" fill="white"/>', $canvasWidth, $canvasHeight);
        // Styles
        $svg .= '<defs><style>' .
            '.dieline { stroke: black; stroke-width: 1.5; fill: none; }' .
            '.arrow { stroke: #333; stroke-width: 1.5; fill: none; }' .
            '.label { font-family: Arial, sans-serif; font-size: 12px; fill: #333; }' .
            '.label-bold { font-family: Arial, sans-serif; font-size: 16px; font-weight: bold; fill: #000; }' . // Increased font size for title
            '</style>' .
            '<marker id="arrowhead" markerWidth="10" markerHeight="10" refX="0" refY="5" orient="auto">' .
            '<polygon points="0 0,10 5,0 10" fill="#333"/>' .
            '</marker>' .
            '<marker id="arrowhead-reverse" markerWidth="10" markerHeight="10" refX="10" refY="5" orient="auto">' .
            '<polygon points="10 0,0 5,10 10" fill="#333"/>' .
            '</marker>' .
            '</defs>';

        // Title: Item Name and Carton Size
        // Calculation: $textGapMM above the top-most dimension arrow
        // Top flaps start at $flapY = $padding + $titleHeight
        // Top dimension arrow is at $lengthY = $flapY - $arrowOffset
        
        $flapY = $padding + $titleHeight;
        $topArrowY = $flapY - $arrowOffset;
        $textGapPixels = $textGapMM * $scale;
        
        $centerX = $canvasWidth / 2;
        $titleY = $topArrowY - $textGapPixels;
        
        $svg .= sprintf(
            '<text x="%f" y="%f" text-anchor="middle" class="label-bold">Carton Size: %s x %s x %s mm | Item Name: %s</text>',
            $centerX,
            $titleY,
            htmlspecialchars($length),
            htmlspecialchars($width),
            htmlspecialchars($height),
            htmlspecialchars($itemName)
        );

        // Panel definitions (order of panels around the body)
        $panels = [
            ['width' => $glueFlap, 'label' => 'Glue Flap'],
            ['width' => $length,   'label' => 'Length Panel'],
            ['width' => $width,    'label' => 'Width Panel'],
            ['width' => $length,   'label' => 'Length Panel'],
            ['width' => $width,    'label' => 'Width Panel'],
        ];

        // Draw outer rectangle path
        $bodyPath = sprintf('M %f %f', $startX, $startY);
        foreach ($panels as $panel) {
            $panelWidth = $panel['width'] * $scale;
            $bodyPath .= sprintf(' h %f', $panelWidth);
        }
        $bodyPath .= sprintf(' v %f', $height * $scale);
        foreach (array_reverse($panels) as $panel) {
            $panelWidth = $panel['width'] * $scale;
            $bodyPath .= sprintf(' h -%f', $panelWidth);
        }
        $bodyPath .= ' Z';
        $svg .= sprintf('<path d="%s" class="dieline"/>', $bodyPath);

        // Vertical panel dividers
        $currentX = $startX;
        foreach ($panels as $i => $panel) {
            $currentX += $panel['width'] * $scale;
            if ($i < count($panels) - 1) {
                $svg .= sprintf(
                    '<line x1="%f" y1="%f" x2="%f" y2="%f" class="dieline"/>',
                    $currentX,
                    $startY,
                    $currentX,
                    $startY + ($height * $scale)
                );
            }
        }

        // Top flaps
        $currentX = $startX + ($glueFlap * $scale);
        $flapY   = $startY - ($flap1 * $scale);
        for ($i = 0; $i < 4; $i++) {
            $panelWidth = ($i % 2 == 0) ? $length * $scale : $width * $scale;
            $svg .= sprintf(
                '<rect x="%f" y="%f" width="%f" height="%f" class="dieline"/>',
                $currentX,
                $flapY,
                $panelWidth,
                $flap1 * $scale
            );
            $currentX += $panelWidth;
        }

        // Bottom flaps
        $currentX     = $startX + ($glueFlap * $scale);
        $bottomFlapY = $startY + ($height * $scale);
        for ($i = 0; $i < 4; $i++) {
            $panelWidth = ($i % 2 == 0) ? $length * $scale : $width * $scale;
            $svg .= sprintf(
                '<rect x="%f" y="%f" width="%f" height="%f" class="dieline"/>',
                $currentX,
                $bottomFlapY,
                $panelWidth,
                $flap2 * $scale
            );
            $currentX += $panelWidth;
        }

        // Dimension arrows and labels
        $arrowOffset = 20;
        // Length (top)
        $lengthX1 = $startX + ($glueFlap * $scale);
        $lengthX2 = $lengthX1 + ($length * $scale);
        $lengthY  = $flapY - $arrowOffset;
        $svg .= $this->drawDimensionArrow($lengthX1, $lengthY, $lengthX2, $lengthY, "L = {$length} mm");
        // Width (top)
        $widthX1 = $lengthX2;
        $widthX2 = $widthX1 + ($width * $scale);
        $svg .= $this->drawDimensionArrow($widthX1, $lengthY, $widthX2, $lengthY, "W = {$width} mm");
        // Height (right side)
        $heightX  = $startX + ($totalWidth * $scale) + $arrowOffset;
        $heightY1 = $startY;
        $heightY2 = $startY + ($height * $scale);
        $svg .= $this->drawDimensionArrow($heightX, $heightY1, $heightX, $heightY2, "H = {$height} mm", true);

        // Close SVG
        $svg .= '</svg>';
        return $svg;
    }

    /**
     * Draw dimension arrow with optional vertical orientation
     */
    private function drawDimensionArrow($x1, $y1, $x2, $y2, $label, $vertical = false)
    {
        $svg = '';
        // Arrow line
        // Use marker-start with the reverse arrowhead and marker-end with the standard arrowhead
        $svg .= sprintf(
            '<line x1="%f" y1="%f" x2="%f" y2="%f" class="arrow" marker-start="url(#arrowhead-reverse)" marker-end="url(#arrowhead)"/>',
            $x1, $y1, $x2, $y2
        );
        // Label positioning
        $labelX = ($x1 + $x2) / 2;
        $labelY = ($y1 + $y2) / 2;
        if ($vertical) {
            $svg .= sprintf(
                '<text x="%f" y="%f" text-anchor="middle" class="label" transform="rotate(-90 %f %f)">%s</text>',
                $labelX + 15,
                $labelY,
                $labelX + 15,
                $labelY,
                htmlspecialchars($label)
            );
        } else {
            $svg .= sprintf(
                '<text x="%f" y="%f" text-anchor="middle" class="label">%s</text>',
                $labelX,
                $labelY - 5,
                htmlspecialchars($label)
            );
        }
        return $svg;
    }
}
