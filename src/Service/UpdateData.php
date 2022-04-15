<?php

namespace App\Service;
use App\Entity\Category;
use App\Entity\Currency;
use App\Entity\DeliveryOptions;
use App\Entity\Offer;
use App\Entity\Params;
use App\Entity\Picture;
use App\Entity\SalesNotes;
use App\Entity\Shop;
use App\Entity\Vendor;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UpdateData
{
    private ObjectManager $manager;
    private $client;

    public function __construct(ManagerRegistry $doctrine, HttpClientInterface $client)
    {
        $this->manager = $doctrine->getManager();
        $this->client = $client;
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
        if($shop_id){
            $this->updateCategories($xml->shop->categories->category);
            $this->updateCurrencies($xml->shop->currencies->currency);
            $this->updateDeliveryOptions($xml->shop->$deliveryOptions);
            $this->updateOffers($xml->shop->offers, $shop_id);
        }
    }

    private function updateShop($xml)
    {
        $shop = $this->manager->getRepository(Shop::class)->findOneBy(['url' => $xml->shop->url]);
        if($shop == null)
        {
            $shop = new Shop;
        }
        else{
            if(strtotime($xml->attributes()->date) <= strtotime($shop->getUpdatedAt())){
                return false;
            }
        }

        $shop->setName($xml->shop->name);
        $shop->setCompany($xml->shop->company);
        $shop->setUrl($xml->shop->url);
        $shop->setUpdatedAt($xml->attributes()->date);
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

           $one_category = $this->manager->getRepository(Category::class)->findOneBy(['id' => (int)$category->attributes()->id]);
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

        $currency = $this->manager->getRepository(Currency::class)->findOneBy(['name' => $xml->attributes()->id]);
        if($currency == null){
            $currency =  new Currency();
            $currency->setName($xml->attributes()->id);
            $currency->setRate((int)$xml->attributes()->rate);

            $this->manager->persist($currency);
            $this->manager->flush();
        }
    }

    private function updateDeliveryOptions($xml)
    {
        foreach ($xml->option as $option){
            $one_option = $this->manager->getRepository(DeliveryOptions::class)->findOneBy(['days' => $option->attributes()->days,'cost' => (int)$option->attributes()->cost]);
            if($one_option == null){
                $one_option = new DeliveryOptions();
                $one_option->setDays($option->attributes()->days);
                $one_option->setCost((int)$option->attributes()->cost);
                $this->manager->persist($one_option);
                $this->manager->flush();
            }
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
                $this->parseVendor($offer->vendor)
            );
            $one_offer->setShop(
                $this->manager->getRepository(Shop::class)->findOneBy(['id' => $shop_id])
            );

            $this->manager->persist($one_offer);
            $this->manager->flush();

            $this->parseSalesNotes($offer->sales_notes, $one_offer);
            $this->parseParams($offer->param, $one_offer);
            $this->parseImages($offer->picture, $one_offer);
        }
    }



    /**
     * @param $xml
     * @return void
     */
    private function parseVendor($name)
    {
        $vendor = $this->manager->getRepository(Vendor::class)->findOneBy(['name' => $name]);
        if( $vendor == null && $name != null){
            $vendor = new Vendor();
            $vendor->setName($name);
            $this->manager->persist($vendor);
            $this->manager->flush();
        }

        return $vendor;
    }

    /**
     * @param $string
     * @param Offer $one_offer
     * @return void
     */
    private function parseSalesNotes( $string, Offer $one_offer){
        $string = str_replace('.', "," , $string);
        $array = explode(', ', $string);
        foreach ($array as $one){
            if($one != ''){
                $one =mb_strtolower( str_replace(",", '', $one));
                $sale_note = $this->manager->getRepository(SalesNotes::class)->findOneBy(['name' =>$one]) ?? new SalesNotes();
                $sale_note->setName($one);
                $sale_note->addOffer($one_offer);
                $this->manager->persist($sale_note);
                $this->manager->flush();
            }
        }

    }

    /**
     * @param $xml
     * @param Offer $one_offer
     * @return void
     */
    private function parseParams($xml, Offer $one_offer)
    {
        foreach ($xml as $param){
            $one_param = $this->manager->getRepository(Params::class)->findOneBy(['name' =>$param->attributes()->name, 'value' => $param, 'offer' => $one_offer->getId()]);
            if($one_param == null){
                $one_param =new Params();
                $one_param->setName($param->attributes()->name);
                $one_param->setValue($param);
                $one_param->setOffer($one_offer);
                $this->manager->persist($one_param);
                $this->manager->flush();
            }


        }
    }

    private function parseImages($xml,$one_offer)
    {
        foreach ($xml as $one){
//                $picture_array = explode('/', $one);
//                $filename = end($picture_array);
                $path = '/uploads/';
                $picture = $this->manager->getRepository(Picture::class)->findOneBy(['name' => $one]) ?? new Picture();
              /*
               * ссылки на картинки уже не рабочие
               */
//                $picture->setName($filename);
                $picture->setName($one);
                $picture->setOffer($one_offer);
                $this->manager->persist($picture);
                $this->manager->flush();

//                $this->savePicture($one,$path);

        }
    }

    /**
     * @param $filename
     * @return false|mixed|string
     */
    private function getExtension($filename)
    {
        $array = explode(".", $filename);
        return end($array);
    }

    private function savePicture($one, $path){
        $picture_array = explode('/', $one);
        $filename = end($picture_array);
        if(!file_exists($path.$filename)
            && in_array($this->getExtension(end($picture_array)),['jpg','png'])
            && $this->checkStatus($one) === '200'){
            $data = file_get_contents($one);
            file_put_contents($path.$filename, $data);
        }
    }
    /**
     * @param $url
     * @return int
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function checkStatus($url){
        $response = $this->client->request('GET', $url);
        return $response->getStatusCode();
    }



}