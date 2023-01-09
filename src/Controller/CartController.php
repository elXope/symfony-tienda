<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path:'/cart')]
class CartController extends AbstractController
{
    private $doctrine;
    private $repository;
    private $cart;
    //Le inyectamos CartService como una dependencia
    public  function __construct(ManagerRegistry $doctrine, CartService $cart)
    {
        $this->doctrine = $doctrine;
        $this->repository = $doctrine->getRepository(Product::class);
        $this->cart = $cart;
    }

    #[Route('/', name: 'cart')]
    public function index(): Response
    {
        $products = $this->repository->getFromCart($this->cart);
        //hay que añadir la cantidad de cada producto
        $items = [];
        $totalCart = 0;
        foreach($products as $product){
            $item = [
                "id"=> $product->getId(),
                "name" => $product->getName(),
                "price" => $product->getPrice(),
                "photo" => $product->getPhoto(),
                "quantity" => $this->cart->getCart()[$product->getId()]
            ];
            $totalCart += $item["quantity"] * $item["price"];
            $items[] = $item;
        }

        return $this->render('cart/index.html.twig', ['items' => $items, 'totalCart' => $totalCart]);
    }


    #[Route('/add/{id}', name: 'cart_add', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function cart_add(int $id): JsonResponse
    {
        $product = $this->repository->find($id);
        if (!$product)
            return new JsonResponse("[]", Response::HTTP_NOT_FOUND);

        $this->cart->add($id, 1);
        $data = [
            "id"=> $product->getId(),
            "name" => $product->getName(),
            "price" => $product->getPrice(),
            "photo" => $product->getPhoto(),
            "quantity" => $this->cart->getCart()[$product->getId()]
        ];
        return new JsonResponse($data, Response::HTTP_OK);

    }

    #[Route('/update/{id}/{quantity}', name:'cart_update', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function update(int $id, int $quantity = 1): JsonResponse
    {
        $this->cart->update($id, $quantity);
        $nItems = ["totalItems" => $this->cart->getTotalItems()];
        return new JsonResponse($nItems, Response::HTTP_OK);
    }

    #[Route('/delete/{id}', name:'cart_delete', methods:'POST', requirements: ['id' => '\d+'])]
    public function delete(int $id): JsonResponse
    {
        $this->cart->delete($id);
        $nItems = ["totalItems" => $this->cart->getTotalItems()];
        return new JsonResponse($nItems, Response::HTTP_OK);
    }
}
