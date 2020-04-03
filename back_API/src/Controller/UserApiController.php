<?php


namespace App\Controller;

use App\Controller\ApiAbstractController;
use App\Entity\User;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class UserApiController extends ApiAbstractController
{
    private function createUser($email, $password, $name='', $darkm=false) {
        $user = new User();

        if (null == $email or null == $password)
            return null;

        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setName($name);
        $user->setRoles([1]);
        $user->setDarkModeAllowed(filter_var((string)$darkm, FILTER_VALIDATE_BOOLEAN));

        $this->persistEntityManager($user);
        $this->flushEntityManager();
        $this->refreshEntityManager($user);

        return $user;
    }

    private function createToken($user) {
        $apikey_json = $user->getApikey();

        if (null == $apikey_json)
            $apikey_json = [];
        do {
            $apikey = sha1(random_bytes(30));;
            $refreshToken = bin2hex(random_bytes(10));
        } while($this->tokenExist($apikey_json, $apikey, $refreshToken));

        $new_token['apikey'] = $apikey;
        $new_token['refreshToken'] = $refreshToken;
        $new_token['expiresAt'] = new DateTime('+1 hour');

        array_push($apikey_json, $new_token);

        $user->setApikey($apikey_json);
        $this->flushEntityManager();

        return $new_token;
    }

    private function refreshToken($user, $apikey, $refreshToken) {
        $apikey_json = $user->getApikey();
        $new_token = null;

        if (null == $apikey_json or null == $apikey or null == $refreshToken)
            return $new_token;

        foreach ($apikey_json as $elm)
            if ($elm['refreshToken'] == $refreshToken and $elm['apikey'] == $apikey) {
                do {
                    $new_apikey = sha1(random_bytes(30));
                } while($this->tokenExist($apikey_json, $new_apikey, null));
                $elm['apikey'] = $new_apikey;
                $elm['expiresAt'] = new DateTime('+1 hour');
                $new_token = $elm;
                break;
            }

        if (null != $new_token) {
            $user->setApikey($apikey_json);
            $this->flushEntityManager();
        }

        return $new_token;
    }

    /**
     * @Route("/api/{username}/connect", methods={"POST"})
     * @param Request $request
     * @param $username
     * @return JsonResponse
     */
    public function user_connect(Request $request, $username)
    {
        $password = $request->query->get('password');

        $elm = $this->getUserClientByUsername($username);

        if (null == $elm or null == $password)
            $res['status'] = 404;
        else if (password_verify($password, $elm->getPassword())) {
            $res = $this->createToken($elm);
            $res['status'] = 200;
        } else
            $res['status'] = 401;
        return new JsonResponse($res, $res['status']);
    }

    /**
     * @Route("/api/{username}/refresh", methods={"PATCH"})
     * @param Request $request
     * @param $username
     * @return JsonResponse
     */
    public function user_refresh(Request $request, $username)
    {
        $refreshToken = $request->query->get('refreshToken');
        $apikey = $request->headers->get('Authorization');

        $elm = $this->getUserClientByUsername($username);

        if (null == $elm or null == $apikey or null == $refreshToken)
            return new JsonResponse(['status' => 404], 404);
        $res = $this->refreshToken($elm, $apikey, $refreshToken);
        if (null != $res)
            $res['status'] = 200;
        else
            $res['status'] = 401;
        return new JsonResponse($res, $res['status']);
    }

    /**
     * @Route("/api/{username}/info", methods={"GET"})
     * @param Request $request
     * @param $username
     * @return JsonResponse
     */
    public function user_info(Request $request, $username)
    {
        $user = $this->getUserClientByUsername($username);

        $res['status'] = $this->isTokenValid($request, $user);

        if (200 != $res['status'])
            return new JsonResponse($res, $res['status']);

        $res['user'] = $this->elmUserGenerator($user);

        return new JsonResponse($res, $res['status']);
    }

    /**
     * @Route("/api/{username}/update", methods={"PATCH"})
     * @param Request $request
     * @param $username
     * @return JsonResponse
     */
    public function user_update(Request $request, $username)
    {
        $user = $this->getUserClientByUsername($username);

        $res['status'] = $this->isTokenValid($request, $user);

        if (200 != $res['status'])
            return new JsonResponse($res, $res['status']);

        $name = $request->query->get('name', null);
        $new_password = $request->query->get('password', null);
        $darkm = $request->query->get('darkm', null);

        if (null != $new_password)
            $user->setPassword(password_hash($new_password, PASSWORD_DEFAULT));
        if (null != $darkm)
            $user->setDarkModeAllowed(filter_var((string)$darkm, FILTER_VALIDATE_BOOLEAN));
        if (null != $name)
            $user->setName($name);

        $res['user'] = $this->elmUserGenerator($user);

        return new JsonResponse($res, $res['status']);
    }

    /**
     * @Route("/api/sign-up", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function user_sign_up(Request $request)
    {
        $email = $request->query->get('email');
        $password = $request->query->get('password');
        $name = $request->query->get('name');
        $darkm = $request->query->get('darkm');

        $user = $this->createUser($email, $password, $name, $darkm);

        if (null == $user)
            return new JsonResponse(['status' => 422], 422);
        else
            $res['status'] = 200;

        $res['user'] = $this->elmUserGenerator($user);

        return new JsonResponse($res, $res['status']);
    }
}