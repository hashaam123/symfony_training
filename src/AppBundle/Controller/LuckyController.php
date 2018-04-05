<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LuckyController extends Controller
{

    /**
     * @route("/lucky/home", name="main")
     */
    public function home()
    {
        return new Response("Home page ");
    }

    /**
     * @Route("/lucky/number/{bash}/two/{hash}", name="luck", requirements={"bash" = "[0-9]+"}, defaults={"bash" = "123", "hash"="hashaam"})
     *
     */
    public function numberAction($hash, $bash)
    {
        $number = mt_rand(0, 100);

        return new Response("number is $number and $bash and $hash");
    }


    /**
     * @Route("/lucky/list/{slug}", name="blog_show")
     */

    public function routeAction($slug)
    {
        return new Response($slug);
    }

    public function getURLAction()
    {
        $url = $this->generateUrl(
            'blog_show',
            array('slug' => 'saf',
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
        return new Response($url);
    }

    public function funAction($str)
    {
        return new Response($str);
    }

    /**
     * @route("/lucky/templete", name="templete")
     */
    public function templete(Request $request)
    {
        $get = $request->get('page');
        $host = $request->getHttpHost();
        $contenttype = $request->headers->get("content_type");
        $list = array($get, $host, $contenttype, "fourth", "fifth");
        return $this->render("templete.html.twig", array("list" => $list));
    }

    /**
     * @route("/lucky/getfile", name="getfile")
     */
    public function getFile()
    {
        $path = $this->container->getParameter('kernel.root_dir');
        $path = str_replace("/app", "", $path) . "/web/uploads/file.png";
        $file = new File($path);
        return $this->file($file, "my_file", ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @route("/lucky/section", name="section")
     */
    public function section()
    {
        return new Response("Home page");
    }

    /**
     * @route("/lucky/move", name="move")
     */
    public function move()
    {
        return $this->redirectToRoute("main");
    }

    /**
     * @route("/lucky/google")
     */
    public function google(Request $request)
    {
        return $this->redirect("https://www.google.com");
    }

    /**
     * @route("/lucky/user")
     */
    public function userSession(SessionInterface $session)
    {
        $str = $session->get("user");
        if(isset($str)) {
            return new Response("session is already created as " . $str);
        } else {
            $session->set("user", "your session");
            return new Response("session created successfully");
        }
    }

    /**
     * @route("/lucky/error")
     */
    public function error()
    {
//        $file = new File("abc");
//        return new Response("ok");
        throw $this->createNotFoundException("not found");
    }

    /**
     * @route("/lucky/flashes")
     */
    public function flashes()
    {

        $this->addFlash("1", "php");
        $this->addFlash("2", "symfony");
        $this->addFlash("3", "laravel");
        return $this->render("flashes.html.twig");
    }

    /**
     * @route("/lucky/temp", name="temp")
     */
    public function temp(Request $request)
    {
        $_SERVER["REMOTE_ADDR"] = "1.1.1.1";
        $addr = $_SERVER["REMOTE_ADDR"];
        return $this->redirectToRoute("temp1");
    }

    /**
     * @route("/lucky/temp1", name="temp1")
     */
    public function temp1(Request $request)
    {
        $addr = $_SERVER["REMOTE_ADDR"];
        return new Response($addr);
    }

}

