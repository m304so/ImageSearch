<?php
class SearchFormCest 
{
    public function submitEmptyForm(\FunctionalTester $I)
    {
        $I->submitForm('#search-form', []);
        $I->expectTo('see validations errors');
        $I->see('Searh cannot be blank');
    }

    public function submitFormSuccessfully(\FunctionalTester $I)
    {
        $I->see('Thank you for contacting us. We will respond to you as soon as possible.');        
    }
}
