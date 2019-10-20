<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Entity\Pte;
use App\Entity\Tutorial;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends AbstractController
{
    
    /**
     * @Route("/api/account", name="api_account")
     */
    public function accountApi()
    {

        $user = $this->getUser();

        return $this->json($user, 200, [], [
            'groups' => ['main']
        ]);

    }





    /**
     * @Route("/account/pte", name="app_account_pte")
     */
    public function accountPte(Request $request, ValidatorInterface $validator)
    {


        if($request->isMethod('POST')){

            $data = json_decode($request->getContent(), true);

            $submittype = $data['submittype'];

            if($submittype==='time_mgmt'){
                $item = $data['item'];
                $task = $data['task'];
                $date= $data['date'];
                $time= $data['time'];

                if(empty($item) || empty($task) || empty($date) || empty($time)){
                    return new JsonResponse(
                        [
                            'status' => 'fail_empty',
                        ],
                        JsonResponse::HTTP_CREATED
                    );
                }

                if(!is_numeric($time)){
                    return new JsonResponse(
                        [
                            'status' => 'fail_illegal_not_number',
                        ],
                        JsonResponse::HTTP_CREATED
                    );
                }
                if($time<0){
                    return new JsonResponse(
                        [
                            'status' => 'fail_illegal_less_than_0',
                        ],
                        JsonResponse::HTTP_CREATED
                    );
                }

                $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
                $username = $this->getUser()->getUsername();

                $entityManager = $this->getDoctrine()->getManager();

                $Listening=array("SST","WFD","LCSA","LCMA","LFIB","HCS","SMW","HIW");
                $Speaking=array("RA","DI","RS","RL","ASQ");
                $Reading=array("RCSA","RCMA","Re-order","RFIB");
                $Writing=array("SWT","Essay");
                $Other=array("Practice listening");

                $pteItemType = 'Other';

                if(in_array($item, $Listening)){
                    $pteItemType = "Listening";
                }elseif (in_array($item, $Speaking)){
                    $pteItemType = "Speaking";
                }elseif (in_array($item, $Reading)){
                    $pteItemType = "Reading";
                }elseif (in_array($item, $Writing)){
                    $pteItemType = "Writing";
                }elseif (in_array($item, $Other)){
                    $pteItemType = "Other";
                }

                $pte = new Pte();
                $pte->setUsername($username);
                $pte->setFinishdate(\DateTime::createFromFormat('Y-m-d', $date));
                $pte->setItem($item);
                $pte->setTask($task);
                $pte->setSpendingtime($time);
                $pte->setItemtype($pteItemType);

//                $errors = $validator->validate($pte);

//                if(count($errors)>0){
//                    return new JsonResponse(
//                        [
//                            'status' => 'fail_to_insert, check your input again',
//                        ],
//                        JsonResponse::HTTP_CREATED
//                    );
//                }

                $entityManager->persist($pte);

                $entityManager->flush();


                return new JsonResponse(
                    [
                        'status' => 'ok',
                    ],
                    JsonResponse::HTTP_CREATED
                );
            }

            if($submittype==='tutorial_mgmt'){
                $date= $data['date'];
                $time= $data['time'];

                if(empty($date) || empty($time)){
                    return new JsonResponse(
                        [
                            'status' => 'fail_empty',
                        ],
                        JsonResponse::HTTP_CREATED
                    );
                }
                if(!is_numeric($time)){
                    return new JsonResponse(
                        [
                            'status' => 'fail_illegal_not_number',
                        ],
                        JsonResponse::HTTP_CREATED
                    );
                }
                if($time<0){
                    return new JsonResponse(
                        [
                            'status' => 'fail_illegal_less_than_0',
                        ],
                        JsonResponse::HTTP_CREATED
                    );
                }

                $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
                $username = $this->getUser()->getUsername();

                $entityManager = $this->getDoctrine()->getManager();

                $tutorial = new Tutorial();
                $tutorial->setUsername($username);
                $tutorial->setAttenddate(\DateTime::createFromFormat('Y-m-d', $date));
                $tutorial->setSpendingtime($time);

                $errors = $validator->validate($tutorial);
                if(count($errors)>0){
                    return new JsonResponse(
                        [
                            'status' => 'fail_to_insert, check your input again',
                        ],
                        JsonResponse::HTTP_CREATED
                    );
                }

                $entityManager->persist($tutorial);
                $entityManager->flush();

//                $ptes = $this->getDoctrine()->getRepository(Pte::class)->findAll();
//                $jsonContent = array();
//                foreach ($ptes as $pte){
//                    $jsonContent[] = array($pte->getId(),$pte->getTask(),$pte->getFinishdate(),$pte->getSpendingtime());
//                }
//
//                $json = $this->get('serializer')->serialize($jsonContent, 'json');
//                return new JsonResponse($json, 200, [], true);
                return new JsonResponse(
                    [
                        'status' => 'ok',
                    ],
                    JsonResponse::HTTP_CREATED
                );

            }
        }

        $username = $this->getUser()->getUsername();
        $pte = $this->getDoctrine()->getRepository(Pte::class)->findBy(array('username'=>$username),['finishdate' => 'DESC']);
        $tutorial = $this->getDoctrine()->getRepository(Tutorial::class)->findBy(array('username'=>$username),['attenddate' => 'DESC']);

        return $this->render('security/pte.html.twig', [
            'ptes' => $pte,
            'tutorials' => $tutorial
        ]);


    }

    /**
     * @Route("/account/pte/get", name="app_account_pte_pget")
     */
    public function testPte(Request $request){

        $ptes = $this->getDoctrine()->getRepository(Pte::class)->findAll();
        $jsonContent = array();
        foreach ($ptes as $pte){
            $jsonContent[] = array($pte->getId(),$pte->getTask(),$pte->getFinishdate(),$pte->getSpendingtime());
        }

        $json = $this->get('serializer')->serialize($jsonContent, 'json');
        return new JsonResponse($json, 200, [], true);

    }

    /**
     * @Route("/account/pte/get/time", name="app_account_pte_get_time")
     */
    public function getTime(Request $request){
        $username = $this->getUser()->getUsername();
        $jsonContent = array();
        $list = array("Listening","Reading","Speaking","Writing","Other");

        foreach ($list as $listitem){
            $queryHours = $this->getDoctrine()->getRepository(Pte::class);
            $querySumHours = $queryHours->createQueryBuilder('l')
                ->select("sum(l.spendingtime) as spendingtime")
                ->where('l.username = :username and l.itemtype = :itemtype')
                ->setParameter('username', $username)
                ->setParameter('itemtype', $listitem)
                ->getQuery();
            $result = $querySumHours->getResult();
            $jsonContent[] = [$listitem=>$result];
        }

        $queryHours = $this->getDoctrine()->getRepository(Pte::class);
        $querySumHours = $queryHours->createQueryBuilder('l')
            ->select("sum(l.spendingtime) as spendingtime")
            ->where('l.username = :username')
            ->setParameter('username', $username)
            ->getQuery();
        $result = $querySumHours->getResult();
        $jsonContent[] = ['Total'=>$result];

        $json = $this->get('serializer')->serialize($jsonContent, 'json');

        return new JsonResponse($json, 200, [], true);

    }
    
    
    
    
    /**
     * @Route("/profile", name="app_profile")
     */
    public function index()
    {

        $token = "EAANLvpC8YZBsBANkaLSF19R0gzznWpfkSehK4Q1Opkp2ox6KUZAn5HmhMSJIZB83LVm67iVleMdNDAMaJV0CZAABu9s8sStE1nX1t4T1DMLz5ZB7eO6n8ZBC1PpFR5JZAbqyVL9ZBXaZAxfEJZCVKZBBB1egBy8iiePrKT6hoDzB2GoqgZDZD";
        $url = "https://graph.facebook.com/v4.0/me?fields=id%2Cfirst_name%2Clast_name&access_token=".$token;

        $client = HttpClient::create();
        $response = $client->request('GET', $url);
        $content = $response->getContent();
        $content = substr($content,1);
        $content = substr($content,0,-1);
        $items = explode(",",$content);
        $content = array();
        foreach ($items as $item){
            $info = explode(":",$item);
            array_push($content, $info[1]);
        }

        return $this->render('default/index.html.twig',[
            "content"=>$content
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //    $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    
    /**
     * @Route("/privacy", name="app_privacy")
     */
    public function privacy()
    {
        return $this->render('security/home.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @Route("/", name="app_home")
     */
    public function home()
    {
        return $this->render('security/home.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }


    


}
