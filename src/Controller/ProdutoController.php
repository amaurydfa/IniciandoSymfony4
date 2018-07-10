<?php

namespace App\Controller;

use App\Entity\Produto;
use App\Form\ProdutoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ProdutoController extends Controller
{
    /**
     * @Route("/produto", name="listar_produto")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $produtos = $em->getRepository(Produto::class)->findAll();

        return $this->render("produto/index.html.twig", [
            'produtos' => $produtos
        ]);
    }

    /**
     * @param Request $request
     *
     * @Route("/produto/cadastrar", name="cadastrar_produto")
     *
     */
    public function create(Request $request)
    {
        $produto = new Produto();

        $form = $this->createForm(ProdutoType::class, $produto);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            $em->persist($produto);
            $em->flush();

            $this->get('session')->getFlashBag()->set('success', 'Produto foi salvo com sucesso!');

            return $this->redirectToRoute('listar_produto');

        }

        return $this->render("produto/create.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("produto/editar/{id}", name="editar_produto")
     */

    public function update(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $produto = $em->getRepository(Produto::class)->find($id);

        $form = $this->createForm(ProdutoType::class, $produto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($produto);
            $em->flush();

            $this->get("session")->getFlashBag()->set("success", "O produto " . $produto->getNome() . "foi alterado com sucesso!" );
            return $this->redirectToRoute("listar_produto");
        }

        return $this->render('produto/update.html.twig', [
            'produto' => $produto,
            'form' => $form->createview()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @Route("produto/visualizar/{id}", name="visualizar_produto")
     *
     * @return Response
     */
    public function view(Request $request, $id){

        $em = $this->getDoctrine()->getManager();
        $produto = $em->getRepository(Produto::class)->find($id);
        return $this->render('produto/view.html.twig', [
            'produto' => $produto
        ]);
    }
}
