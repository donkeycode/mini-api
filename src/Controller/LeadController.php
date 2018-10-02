<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Repository\LeadRepository;
use App\Entity\Lead;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Form\LeadType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * @Route("/leads")
 */
class LeadController extends FOSRestController
{
    /**
     * @Rest\Get
     * @Rest\View(serializerGroups={"leads_cget"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns all leads",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Lead::class, groups={"leads_cget"}))
     *     )
     * )
     */
    public function cget(LeadRepository $leadRepository)
    {
        return $leadRepository->findAll();
    }

    /**
     * @Rest\Get("/{id}")
     * @Rest\View(serializerGroups={"leads_get"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the lead id",
     *     @Model(type=Lead::class, groups={"leads_get"})
     * )
     */
    public function getLead(Lead $lead)
    {
        return $lead;
    }


    /**
     * @Rest\Post
     * @Rest\View(serializerGroups={"leads_get"}, statusCode=201)
     * @SWG\Response(
     *     response=201,
     *     description="Create one lead",
     *     @Model(type=Lead::class, groups={"leads_get"})
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Fail to insert a lead"
     * )
     * @SWG\Parameter(
     *   name="form",
     *   in="body",
     *   description="Bla Bla Bla",
     *   @Model(type=LeadType::class)
     * )
     */
    public function post(Request $request)
    {
        $form = $this->createForm(LeadType::class);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $lead = $form->getData();

            $this->getDoctrine()->getManager()->persist($lead);
            $this->getDoctrine()->getManager()->flush();

            return $lead;
        }

        return $this->view(['form' => $form], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Put("/{id}")
     * @Rest\View(statusCode=204)
     * @SWG\Response(
     *     response=204,
     *     description="Lead updated"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Fail to update a lead"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Lead not found"
     * )
     * @SWG\Parameter(
     *   name="form",
     *   in="body",
     *   description="Bla Bla Bla",
     *   @Model(type=LeadType::class)
     * )
     */
    public function put(Lead $lead, Request $request)
    {
        $form = $this->createForm(LeadType::class, $lead);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return null;
        }

        return $this->view(['form' => $form], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Delete("/{id}")
     * @Rest\View(statusCode=204)
     * @SWG\Response(
     *     response=204,
     *     description="Delete one lead",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Lead not found"
     * )
     */
    public function delete(Lead $lead)
    {
        $this->getDoctrine()->getManager()->remove($lead);
        $this->getDoctrine()->getManager()->flush();

        return null;
    }
}
