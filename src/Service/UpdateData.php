<?php

namespace App\Service;
use App\Entity\Category;
use App\Entity\Currency;
use App\Entity\DeliveryOptions;
use App\Entity\Offer;
use App\Entity\Shop;
use App\Entity\Vendor;
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

        $deliveryOptions = 'delivery-options';

        $shop_id = $this->updateShop($xml);
        $this->updateCategories($xml->shop->categories->category);
        $this->updateCurrencies($xml->shop->currencies->currency);
        $this->updateDeliveryOptions($xml->shop->$deliveryOptions);
        $this->updateOffers($xml->shop->offers, $shop_id);

    }

    private function updateShop($xml)
    {
        $shop = $this->manager->getRepository(Shop::class)->findOneBy(['url' => $xml->shop->url]) ?? new Shop();
        $shop->setName($xml->shop->name);
        $shop->setCompany($xml->shop->company);
        $shop->setUrl($xml->shop->url);
        $this->manager->persist($shop);
        $this->manager->flush();
        return $shop->getId();
    }

    /**
     * @param $xml
     */
    private function updateCategories($xml)
    {
       foreach ($xml as $category){

           $one_category = $this->manager->getRepository(Category::class)->findOneBy(['id' => (int)$category->attributes()->id]) ?? new Category();
           $one_category->setId((int)$category->attributes()->id);
            if($category->attributes()->parentId){
               $parent_category= $this->manager->getRepository(Category::class)->findOneBy(['id' => (int)$category->attributes()->parentId]);
               $one_category->setParent($parent_category);
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

        $currency = $this->manager->getRepository(Currency::class)->findOneBy(['name' => $xml->attributes()->id]) ?? new Currency();
        $currency->setName($xml->attributes()->id);
        $currency->setRate((int)$xml->attributes()->rate);

        $this->manager->persist($currency);
        $this->manager->flush();
    }

    private function updateDeliveryOptions($xml)
    {
        foreach ($xml->option as $option){
            $one_option = $this->manager->getRepository(DeliveryOptions::class)->findOneBy(['days' => $option->attributes()->days]) ?? new DeliveryOptions();
            $one_option->setDays($option->attributes()->days);
            $one_option->setCost((int)$option->attributes()->cost);
            $this->manager->persist($one_option);
            $this->manager->flush();
        }
    }

    /**
     * @param $xml
     * @return void
     */
    private function updateOffers($xml, $shop_id)
    {
       // var_dump($xml->offer);
        foreach ($xml->offer as $offer) {
            $one_offer = $this->manager->getRepository(Offer::class)->findOneBy(['barcode' => (int)$offer->barcode]) ?? new Offer();
            $one_offer->setBarcode((int)$offer->barcode);
            $one_offer->setUrl($offer->url);
            $one_offer->setPrice((int)$offer->price);
            $one_offer->setStore((int)$offer->store);
            $one_offer->setPickup((int)$offer->pickup);
            $one_offer->setDelivery((int)$offer->delivery);
            $one_offer->setModel($offer->model);
            $one_offer->setTypePrefix($offer->typePrefix);
            $one_offer->setDescription($offer->description);
            $one_offer->setVendorCode($offer->vendorCode);
            $one_offer->setDeliveryOption(
                $this->manager->getRepository(DeliveryOptions::class)->findOneBy(['id' => 1])

            );
            $one_offer->setCurrency(
                $this->manager->getRepository(Currency::class)->findOneBy(['name' => $offer->currencyId])
            );
            $one_offer->setCategory(
                $this->manager->getRepository(Category::class)->findOneBy(['id' => $offer->categoryId])
            );
            $one_offer->setVendor(
                $this->manager->getRepository(Vendor::class)->findOneBy(['id' => 1])
            );
            $one_offer->setShop(
                $this->manager->getRepository(Shop::class)->findOneBy(['id' => $shop_id])
            );

            $this->manager->persist($one_offer);
            $this->manager->flush();
        }
    }

    /**
     * @param $xml
     * @return void
     */
    private function parseDeliveryOptions($xml)
    {

    }

}