<?php
/**
 * @author    Daniel Papp <daniel.papp95@gmail.com>
 * @copyright PVA Bt. 2020 All rights reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace App\Models;

/**
 * Class Brand
 * @package App\Models
 */
class Brand
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @var int
     */
    private int $qualityCategory;

    /**
     * Brand constructor.
     * @param string $name
     * @param int    $qualityCategory
     */
    public function __construct(string $name, int $qualityCategory = 1)
    {
        $this->name = $name;

        $this->setQualityCategory($qualityCategory);
    }

    public function getQualityCategory(): ?int
    {
        return isset($this->qualityCategory)
            ? $this->qualityCategory
            : null;
    }

    public function setQualityCategory(int $qualityCategory): self
    {
        if ($qualityCategory < 1) {
            throw new \InvalidArgumentException(
                "Quality category must be greater than 0! [Current value: $qualityCategory]"
            );
        }

        if ($qualityCategory > 5) {
            throw new \InvalidArgumentException(
                "Quality category must be less than 5! [Current value: $qualityCategory]"
            );
        }

        $this->qualityCategory = $qualityCategory;

        return $this;
    }
}
