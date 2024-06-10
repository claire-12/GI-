<?php

class ProductQuote
{
    protected ?WC_Product $product = null;
    protected array $quote;

    public function __construct(string $email)
    {
        $quote = RequestProductQuote::get($email);var_dump($quote);
        $this->quote = reset($quote);
    }

    public function get_product(): WC_Product|null
    {
        if (empty($this->product) && isset($this->quote->object_id)) {
            $this->product = wc_get_product($this->quote->object_id);
        }
        return $this->product;
    }
}
