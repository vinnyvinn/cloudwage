<?php

namespace Payroll\Handlers\Authentication;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticator
{
    protected $request;

    protected $isAjax;

    /**
     * Authenticator constructor.
     *
     * @param $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->isAjax = $request->ajax();
    }


    public static function getLogin()
    {
        return view('auth.login');
    }

    public function authenticate($credentials, $remember)
    {
        $result = "<strong>Whoops!!</strong> Invalid Credentials!";

        try {
            if ($user = Sentinel::authenticate($credentials, $remember)) {
                $result = "Successfully signed in, welcome.";
                $response = redirect()->route('dashboard');

                return $this->processResult(1, $result, $response);
            }

        } catch (\Exception $ex) {
            $result = $ex->getMessage();
        }

        $response = redirect()->back();

        return $this->processResult(0, $result, $response);
    }

    public static function logout()
    {
        Sentinel::logout(null, true);
        return redirect()->route('login.index');
    }

    private function processResult($status, $result, Response $response)
    {
        if ($this->isAjax) {
            return response()->json(
                [
                    'status' => $status,
                    'message' => $result
                ]
            );
        }

        if ($status == 1) {
            flash($result, 'success');
            return $response;
        }

        return $response->withErrors($result);
    }
}
