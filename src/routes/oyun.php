<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;


$app->get('/skor', function (Request $request, Response $response) {

    $db = new Db();
    try{
        $db = $db->connect();

        $courses = $db->query("SELECT * FROM skor ORDER BY skor DESC")->fetchAll(PDO::FETCH_OBJ);

        return $response
            ->withStatus(200)
            ->withHeader("Content-Type", 'application/json')
            ->withJson($courses);

    }catch(PDOException $e){
        return $response->withJson(
            array(
                "error" => array(
                    "text"  => $e->getMessage(),
                    "code"  => $e->getCode()
                )
            )
        );
    }
    $db = null;
});


$app->post('/skor/ekle/{skor_adi}/{skor}', function (Request $request, Response $response) {

    $skor_adi      = $request->getAttribute("skor_adi");
    $skor = $request->getAttribute("skor");

    $db = new Db();
    try{
        $db = $db->connect();
        $statement = "INSERT INTO skor (skor_adi,skor) VALUES(:skor_adi, :skor)";
        $prepare = $db->prepare($statement);

        $prepare->bindParam("skor_adi",$skor_adi );
        $prepare->bindParam("skor",$skor);

        $course = $prepare->execute();

        if($course){
            return $response
                ->withStatus(200)
                ->withHeader("Content-Type", 'application/json')
                ->withJson(array(
                    "text"  => "Başarılı bir şekilde eklendi..."
                ));

        } else {
            return $response
                ->withStatus(500)
                ->withHeader("Content-Type", 'application/json')
                ->withJson(array(
                    "error" => array(
                        "text"  => "Bir problem oluştu."
                    )
                ));
        }

    }catch(PDOException $e){
        return $response->withJson(
            array(
                "error" => array(
                    "text"  => $e->getMessage(),
                    "code"  => $e->getCode()
                )
            )
        );
    }
    $db = null;
});


$app->put('/skor/guncelle/{id}/{skor_adi}/{skor}', function (Request $request, Response $response) {

    $id = $request->getAttribute("id");
    $skor_adi      = $request->getAttribute("skor_adi");
    $skor = $request->getAttribute("skor");


    if($id){

  $skor_adi      = $request->getAttribute("skor_adi");
    $skor = $request->getAttribute("skor");


        $db = new Db();
        try{
            $db = $db->connect();
            $statement = "UPDATE skor SET skor_adi = :skor_adi, skor = :skor WHERE skor_id = $id";
            $prepare = $db->prepare($statement);

            $prepare->bindParam("skor_adi", $skor_adi);
            $prepare->bindParam("skor", $skor);

            $course = $prepare->execute();

            if($course){
                return $response
                    ->withStatus(200)
                    ->withHeader("Content-Type", 'application/json')
                    ->withJson(array(
                        "text"  => "Başarılı bir şekilde güncellendi..."
                    ));

            } else {
                return $response
                    ->withStatus(500)
                    ->withHeader("Content-Type", 'application/json')
                    ->withJson(array(
                        "error" => array(
                            "text"  => "Bir problem oluştu."
                        )
                    ));
            }
        }catch(PDOException $e){
            return $response->withJson(
                array(
                    "error" => array(
                        "text"  => $e->getMessage(),
                        "code"  => $e->getCode()
                    )
                )
            );
        }
        $db = null;
    } else {
        return $response->withStatus(500)->withJson(
            array(
                "error" => array(
                    "text"  => "ID eksik.."
                )
            )
        );
    }

});


$app->delete('/skor/{id}', function (Request $request, Response $response) {

    $id      = $request->getAttribute("id");

    $db = new Db();
    try{
        $db = $db->connect();
        $statement = "DELETE FROM skor WHERE skor_id = :id";
        $prepare = $db->prepare($statement);
        $prepare->bindParam("id", $id);

        $course = $prepare->execute();

        if($course){
            return $response
                ->withStatus(200)
                ->withHeader("Content-Type", 'application/json')
                ->withJson(array(
                    "text"  => "Başarılı bir şekilde silindi..."
                ));

        } else {
            return $response
                ->withStatus(500)
                ->withHeader("Content-Type", 'application/json')
                ->withJson(array(
                    "error" => array(
                        "text"  => "Bir problem oluştu."
                    )
                ));
        }

    }catch(PDOException $e){
        return $response->withJson(
            array(
                "error" => array(
                    "text"  => $e->getMessage(),
                    "code"  => $e->getCode()
                )
            )
        );
    }
    $db = null;
});
