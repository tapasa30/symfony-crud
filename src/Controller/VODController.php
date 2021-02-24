<?php
namespace App\Controller;

use App\Document\User;
use App\Document\VOD;
use App\Service\VODService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;;

class VODController extends Response
{
    /**
     * @Route("/l_C", methods={"POST"})
     */
    public function listAction(Request $request, DocumentManager $dm, VODService $vodService)
    {
        $request = $this->toJsonBody($request);
        $genres = $request->get('genres');
        $currentPage = $request->get('page', 1);
        $vodRepository = $dm->getRepository(VOD::class);
        $vodObjects = $vodRepository->findBy([
            'genres' => $genres
        ], null, 5, (($currentPage - 1) * 5));
        $vodsArray = array_map(function(VOD $vod) use ($vodService) {
            return $vodService->toArray($vod);
        }, $vodObjects);

        return $this->response([
            'contents' => $vodsArray,
            'pages' => [
                'current' => $currentPage,
                'total' => count($vodRepository->findAll())
            ]
        ]);
    }

    /**
     * @Route("/c_C", methods={"POST"})
     *
     */
    public function createAction(Request $request, DocumentManager $dm)
    {
        $request = $this->toJsonBody($request);

        $requiredParameters = [
            'title',
            'duration',
            'genre',
            'image',
            'synopsis',
        ];

        $parameters = $request->request->all();
        $errors = [];
        foreach ($parameters as $param => $value) {
            if (in_array($param, $requiredParameters)) {
                continue;
            }

            $errors[] = $param;
        }

        if (!empty($errors)) {
            $this->setStatusCode(500);

            return $this->response([], [], true, ':o');
        }

        $vod = new VOD();

        $vod->setTitle($parameters['title']);
        $vod->setDuration($parameters['duration']);
        $vod->setGenre($parameters['duration']);
        $vod->setImage($parameters['image']);
        $vod->setSynopsis($parameters['synopsis']);

        $dm->persist($vod);
        $dm->flush();

        return $this->response([], [], false, ':)');
    }

    /**
     * @Route("/d_C", methods={"POST"})
     *
     */
    public function deleteAction(Request $request, DocumentManager $dm)
    {
        $request = $this->toJsonBody($request);
        $vodId = $request->get('id');

        if (empty($vodId)) {
            $this->setStatusCode(400);

            return $this->response([], [], true, 'Missing required parameter "id"');
        }

        $vodRepository = $dm->getRepository(VOD::class);
        $vod = $vodRepository->find($vodId);

        if (empty($vod)) {
            $this->setStatusCode(400);

            return $this->response([], [], true, 'VOD not found');
        }

        $dm->remove($vod);
        $dm->flush();

        return $this->response([], [], false, '');
    }

    /**
     * @Route("/e_C", methods={"POST"})
     *
     */
    public function editAction(Request $request, DocumentManager $dm)
    {
        $request = $this->toJsonBody($request);
        $vodId = $request->get('id');

        if (empty($vodId)) {
            $this->setStatusCode(400);

            return $this->response([], [], true, 'Missing required parameter "id"');
        }

        $vodRepository = $dm->getRepository(VOD::class);
        $vod = $vodRepository->find($vodId);

        if (empty($vod)) {
            $this->setStatusCode(400);

            return $this->response([], [], true, 'VOD not found');
        }

        if ($title = $request->get('title')) {
            $vod->setTitle($title);
        }

        if ($genre = $request->get('genre')) {
            $vod->setTitle($genre);
        }

        if ($image = $request->get('image')) {
            $vod->setTitle($image);
        }

        if ($duration = $request->get('duration')) {
            $vod->setTitle($duration);
        }

        if ($synopsis = $request->get('synopsis')) {
            $vod->setTitle($synopsis);
        }

        $dm->persist($vod);
        $dm->flush();

        return $this->response([], [], false, '');
    }

    /**
     * @Route("/g_C", methods={"POST"})
     *
     */
    public function getAction(Request $request, DocumentManager $dm, VODService $vodService)
    {
        $request = $this->toJsonBody($request);
        $vodId = $request->get('id');

        if (empty($vodId)) {
            $this->setStatusCode(400);

            return $this->response([], [], true, 'Missing required parameter id');
        }

        $vodRepository = $dm->getRepository(VOD::class);
        $vod = $vodRepository->find($vodId);

        if (empty($vod)) {
            $this->setStatusCode(400);

            return $this->response([], [], true, 'VOD not found');
        }

        return $this->response($vodService->toArray($vod));
    }

    /**
     * @Route("/l_F", methods={"POST"})
     *
     */
    public function userFavouritesAction(Request $request, DocumentManager $dm, VODService $vodService)
    {
        $request = $this->toJsonBody($request);
        $userRepository = $dm->getRepository(User::class);

        $user = $userRepository->findOneBy([ 'token' => $request->request->get('token')]);

        if (empty($user)) {
            $this->setStatusCode(400);

            return $this->response([], [], true, 'Logged user not found');
        }

        $userVodsArray = array_map(function(VOD $vod) use ($vodService) {
            return $vodService->toArray($vod);
        }, $user->getFavs()->toArray());

        return $this->response([
            'favs' => $userVodsArray,
        ], [], false, '');
    }

    /**
     * @Route("/a_F", methods={"POST"})
     *
     */
    public function updateUserFavouriteAction(Request $request, DocumentManager $dm)
    {
        $request = $this->toJsonBody($request);
        $vodId = $request->get('id');
        $isFav = $request->get('fav');

        if (empty($vodId) || !isset($isFav)) {
            $this->setStatusCode(400);

            return $this->response([], [], true, 'Missing some required parameter');
        }

        $vodRepository = $vodRepository = $dm->getRepository(VOD::class);
        $vod = $vodRepository->find($vodId);

        if (empty($vod)) {
            $this->setStatusCode(400);

            return $this->response([], [], true, 'VOD not found');
        }

        $userRepository = $dm->getRepository(User::class);
        $user = $userRepository->findOneBy([ 'token' => $request->request->get('token')]);

        if (empty($user)) {
            $this->setStatusCode(400);

            return $this->response([], [], true, 'Logged user not found');
        }

        if (!$user->getFavs()->contains($vod) && $isFav) {
            $user->addFav($vod);
        }

        if ($user->getFavs()->contains($vod) && !$isFav) {
            $user->removeFav($vod);
        }

        $dm->persist($user);
        $dm->flush();

        return $this->response([], [], false, '');
    }
}