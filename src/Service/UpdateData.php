<?php

namespace App\Service;
use App\Entity\Category;
use App\Entity\Shop;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class UpdateData
{
    private ObjectManager $manager;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->manager = $doctrine->getManager();
    }

    /**
     * @return void
     */
    public function parseShop()
    {
        $data = file_get_contents('https://old.iport.ru/mindbox_iport.xml');
        $xml = simplexml_load_string($data);

        $this->updateShop($xml);
        $this->updateCategories($xml->shop->categories->category);
        $this->updateCurrencies($xml->shop->currencies);
        var_dump($xml); die();
    }

    private function updateShop($xml)
    {
        $shop = $this->manager->getRepository(Shop::class)->findOneBy(['url' => $xml->shop->url]) ?? new Shop();
        $shop->setName($xml->shop->name);
        $shop->setCompany($xml->shop->company);
        $shop->setUrl($xml->shop->url);
        $this->manager->persist($shop);
        $this->manager->flush();
    }

    /**
     * @param $xml
     * @return void
     */
    private function updateCategories($xml)
    {
       foreach ($xml as $category){

           foreach ($category->attributes() as $key=>$value) {

               if($key === 'id') {
                   $one_category = $this->manager->getRepository(Category::class)->findOneBy(['id' => (int)$value]) ?? new Category();
                   $one_category->setId((int)$value);
               }
               if($key === 'parentId'){
                   $parent_category= $this->manager->getRepository(Category::class)->findOneBy(['id' => (int)$value]);
                   $one_category->setParent($parent_category);
               }
           }

           $one_category->setTitle($category[0]);
           $this->manager->persist($one_category);
           $this->manager->flush();


       }

    }

    /**
     * @param $xml
     * @return void
     */
    private function updateCurrencies($xml)
    {

    }

    /**
     * @param $xml
     * @return void
     */
    private function parseOffers($xml)
    {

    }

    /**
     * @param $xml
     * @return void
     */
    private function parseDeliveryOptions($xml)
    {

    }

}