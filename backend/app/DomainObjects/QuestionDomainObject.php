<?php

namespace HiEvents\DomainObjects;

use HiEvents\DomainObjects\Enums\QuestionTypeEnum;
use Illuminate\Support\Collection;

class QuestionDomainObject extends Generated\QuestionDomainObjectAbstract
{
    public ?Collection $products = null;

    public function setProducts(?Collection $products): QuestionDomainObject
    {
        $this->products = $products;
        return $this;
    }

    public function getProducts(): ?Collection
    {
        return $this->products;
    }

    public function isMultipleChoice(): bool
    {
        return in_array($this->getType(), [
            QuestionTypeEnum::MULTI_SELECT_DROPDOWN,
            QuestionTypeEnum::CHECKBOX,
        ], true);
    }

    public function setOptions(array|string|null $options): self
    {
        if (is_array($options)) {
            $options = array_filter(array_unique($options));
        }

        $this->options = $options;
        return $this;
    }

}
