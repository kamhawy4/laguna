<?php

namespace App\Services;

class AreaUnitConversionService
{
    const UNIT_SQM = 'sqm';      // Square meters (base unit)
    const UNIT_SQFT = 'sqft';    // Square feet
    
    // 1 square meter = 10.764 square feet
    const SQM_TO_SQFT = 10.764;

    /**
     * Convert area from square meters to target unit
     * 
     * @param float $areaSqm Area in square meters
     * @param string $targetUnit Target unit ('sqm' or 'sqft')
     * @return float
     */
    public function convertFromBase(float $areaSqm, string $targetUnit): float
    {
        $targetUnit = strtolower($targetUnit);

        if ($targetUnit === self::UNIT_SQFT) {
            return round($areaSqm * self::SQM_TO_SQFT, 2);
        }

        return $areaSqm; // Return as-is if unit is sqm or unknown
    }

    /**
     * Convert area from source unit to square meters
     * 
     * @param float $area Area value
     * @param string $sourceUnit Source unit ('sqm' or 'sqft')
     * @return float
     */
    public function convertToBase(float $area, string $sourceUnit): float
    {
        $sourceUnit = strtolower($sourceUnit);

        if ($sourceUnit === self::UNIT_SQFT) {
            return round($area / self::SQM_TO_SQFT, 2);
        }

        return $area; // Return as-is if unit is sqm or unknown
    }

    /**
     * Format area for display with unit label
     * 
     * @param float $areaSqm Area in square meters
     * @param string $unit Target unit
     * @return string
     */
    public function formatArea(float $areaSqm, string $unit): string
    {
        $unit = strtolower($unit);
        $converted = $this->convertFromBase($areaSqm, $unit);
        $label = $unit === self::UNIT_SQFT ? 'sqft' : 'sqm';
        
        return number_format($converted, 2) . ' ' . $label;
    }

    /**
     * Get all supported units
     * 
     * @return array
     */
    public static function getSupportedUnits(): array
    {
        return [
            self::UNIT_SQM => 'Square Meters (sqm)',
            self::UNIT_SQFT => 'Square Feet (sqft)',
        ];
    }

    /**
     * Validate if unit is supported
     * 
     * @param string $unit
     * @return bool
     */
    public static function isValidUnit(string $unit): bool
    {
        return in_array(strtolower($unit), [self::UNIT_SQM, self::UNIT_SQFT]);
    }
}
