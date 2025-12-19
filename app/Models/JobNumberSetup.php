<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobNumberSetup extends Model
{
    use HasFactory;

    protected $fillable = ['prefix', 'current_number', 'padding'];

    /**
     * Generate and return the next job number, incrementing the counter
     */
    public static function getNextJobNumber()
    {
        $setup = self::firstOrCreate(
            [],
            ['prefix' => 'JC-', 'current_number' => 0, 'padding' => 5]
        );

        // Increment the current number
        $setup->increment('current_number');
        
        // Format the job number with padding
        $paddedNumber = str_pad($setup->current_number, $setup->padding, '0', STR_PAD_LEFT);
        
        return $setup->prefix . $paddedNumber;
    }

    /**
     * Parse a job number string (e.g., "JC-QC-00001") and set up the configuration
     */
    public static function parseAndSetup($jobNumber)
    {
        // Extract numeric part from the end
        preg_match('/^(.+?)(\d+)$/', $jobNumber, $matches);
        
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
