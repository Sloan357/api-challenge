#Mytheresa API Challenge

By Afif El Charif

#OVERVIEW

The Challenge was to create a REST API endpoint that, given a list of products, applies some
discounts to them and can be filtered by ‘category’ or ‘priceLessThan’.
Solution Design:
1. Implement the ProductsManager.php which contains the following functions:
        a. listDiscountedProducts(array $productArray)
                i. This function accepts an array as a parameter, the array is the decoded
                and filtered result of the original productList.
                ii. Then it loops over the array and assigns the appropriate discount to each
                product if applicable. (according to the rules provided in the challenge
                document ex: all boots have 30% Off, sku ‘000003’ has 15% Off, and if
                there are overlapping discount then the highest discount becomes the
                applied one)
                iii. Implements a new Result array that is identical to the provided one with a
                minor change on the ‘price’ attribute, instead of an integer it becomes a list
                with the following attributes:
                        1.‘originalPrice’.
                        2.‘finalPrice’: the discounted price, if no discount applies then it
                        remains the same as ‘originalPrice’.
                        3.‘discountPercentage’: if no discount then this value is ‘NULL’.
                        4.‘Currency’: always set to ‘EUR’.
                iv.
                Returns the Result array.
        b. filterProduct(string $category = null, string $priceLessThan = null, $list = null)
                i.Checks if there are any filters passed in the parameters (category and/or
                priceLessThan), if any then retrieves the products matching the conditions
                from the json file and returns a normalized array.ii.
                The $list parameter is for unit testing purposes, since as requested the
                tests must run without networking or filing system, this parameters allows
                us to pass mock data to it.
                c. applyDiscount(int $price, int $percentile = null)
                i.This function accepts the price as a first parameter, and the discount
                percentage as the second parameter otherwise the discount is null.
                ii.If the discount applies then the function returns the discounted price,
                otherwise returns the price as is.
2. Implement the ProductsController.php
        a. The index function has ‘/products’ as its route
        b. The Controller searches the HTTP request for the following string query
        parameters:
                i.‘category’
                ii.‘priceLessThan’
        c. Calls the ProductsManager functions
        d. Truncates the array to a maximum of 5 products
        e. Returns the arrayResult as a JSON response.
3. Implement the a Symfony Command ProductListCommand.php
        a. ‘bin/console app:list-products’
        b. It also accepts two parameters
                i.category
                ii.priceLessThan
        c. The purpose of this command is to be able to run/test the API from a terminal
        level.
4. Implement the ProductsManagerTest.php:
        a. listDiscountedProducts(array $productArray)
                i.testListDiscountedProducts
        b. filterProducts(string $category = null, string $priceLessThan = null, $list = null)
                i.testFilterProductsNoFilters: test the function without passing filters.
                ii.testFilterProductsCategoryFilters: tests the function when passing only a
                category filter.
                iii.testFilterProductsPriceLessThanFilters: tests the function when passing
                only the priceLessThan filter.
                iv.testFilterProductsAllFilters: test the function when passing both filters
        c. applyDiscount(int $price, int $percentile = null)
        i.testApplyDiscount: tests the function when there is a provided discount.
        ii.testApplyNoDiscount: tests the function when no discount is provided.Testing/Running Steps:
                ●Access the terminal
                ●Go inside the project directory
                ●Run the command: ‘symfony server:start’
                ●Run composer install
                ●Running From the Browser:
                        ○http://127.0.0.1:8000/products to have the products from the list unfiltered
                        ○http://127.0.0.1:8000/products?category=boots&priceLessThan=800 to have the
                        products filtered by category and priceLessThan.
                        (category=<CATEGORY_GOES_HERE>,
                        priceLessThan=<PRICE_LIMIT_GOES_HERE>)
                ●The same can be done from Postman.
                Running From the Terminal:
                        ○Without filters: bin/console app:list-products
                        ○With category filter only: bin/console app:list-products
                        <CATEGORY_GOES_HERE>
                        ○With priceLessThan filter only: bin/console app:list-products null
                        <PRICE_LIMIT_GOES_HERE>
                        ○With both Filters: bin/console app:list-products <CATEGORY_GOES_HERE>
                        <PRICE_LIMIT_GOES_HERE>
                ●Running UnitTest from terminal: php bin/phpunit
Running and Publishing on Github
1. Create a new project on github
2. Push the code into the project Repo
3. Add a composer install in Actions to install the dependencies
4. Add Symfony server start command in Actions (Docker or Vagrant with the proper config
files will work as well)
5. Generate your own deploy key
6. Follow the instructions here
7. https://developer.github.com/v3/guides/managing-deploy-keys/#deploy-keys
8. Overwriting the files
a. id_rsa.pub id_rsa With the generated SSH keys.
9. Run composer deploy
