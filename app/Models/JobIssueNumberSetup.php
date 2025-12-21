<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobIssueNumberSetup extends Model
{
    use HasFactory;

    protected $fillable = ['prefix', 'current_number', 'padding'];

    /**
     * Generate and return the next job issue number, incrementing the counter
     */
    public static function getNextIssueNumber()
    {
        $setup = self::firstOrCreate(
            [],
            ['prefix' => 'JI-', 'current_number' => 0, 'padding' => 5]
        );

        // Increment the current number
        $setup->increment('current_number');
        
        // Format the issue number with padding
        $paddedNumber = str_pad($setup->current_number, $setup->padding, '0', STR_PAD_LEFT);
        
        return $setup->prefix . $paddedNumber;
    }

    /**
     * Parse a job issue number string (e.g., "JI-00001") and set up the configuration
     */
    public static function parseAndSetup($issueNumber)
    {
        // Extract numeric part from the end
        preg_match('/^(.+?)(\d+)$/', $issueNumber, $matches);
        
        if (count($matches) === 3) {
            $prefix = $matches[1];
            $number = $matches[2];
            $padding = strlen($number);
            
            $setup = self::firstOrCreate([]);
            $setup->update([
                'prefix' => $prefix,
                'current_number' => (int)$number - 1, // Set to one less so next will be this number
                'padding' => $padding
            ]);
            
            return true;
        }
        
        return false;
    }
}
