<?php

namespace lahaut\BaloonWatcherBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use lahaut\BaloonWatcherBundle\Entity\GPSRecordRepository;
use lahaut\BaloonWatcherBundle\Entity\GPSRecord;
use lahaut\BaloonWatcherBundle\Entity\Scenario;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;

class WSRecordController extends Controller
{
    /**
     * @Route("/saveGpsRecord/{latitude}/{longitude}/{altitude}")
     */
    public function saveGpsRecordAction($latitude, $longitude, $altitude)
    {
        $em = $this->getDoctrine()->getManager();
        $scenario = $this->createNewOrRetrieveLastScenation();

        //create the GPS record
        $gpsRecord = new GPSRecord();
        $gpsRecord->setLatitude(floatval($latitude));
        $gpsRecord->setLongitude(floatval($longitude));
        $gpsRecord->setAltitude($altitude);
        $gpsRecord->setCreationDate(new \DateTime('now'));
        $gpsRecord->setModificationDate(new \DateTime('now'));

        $gpsRecord->setScenario($scenario);

        $em = $this->getDoctrine()->getManager();
        $em->persist($gpsRecord);
        $em->flush();

        return $this->render('BaloonWatcherBundle:Default:insertionSuccessful.html.twig');
    }

    /**
     * @Route("/createNewScenario")
     */
    public function createNewScenarioAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT g FROM BaloonWatcherBundle:Scenario g ORDER BY g.number DESC'
        );
        $scenarioList = $query->getResult();

        $scenario = new Scenario;
        //manage the case if no scenarios were already registred
        if ($scenarioList === null) {
            $scenario->setNumber(0);
        }
        else if (count($scenarioList) === 0) {
            $scenario->setNumber(0);
        }
        else {
            $lastScenario = $scenarioList[0];
            $scenario->setNumber($lastScenario->getNumber() + 1);
        }

        $scenario->setCreationDate(new \DateTime('now'));
        $scenario->setModificationDate(new \DateTime('now'));

        $em->persist($scenario);
        $em->flush();

        return $this->render('BaloonWatcherBundle:Default:scenarioCreationSuccessful.html.twig');

    }

    /**
     * createNewOrRetrieveLastScenation retrieve the last scenario. If no scenarios already exists, a
     * new Scenario instance is created
     *
     * @return Scenario
     */
    private function createNewOrRetrieveLastScenation()
    {
        //Retrieve the last Scenarios registred
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT g FROM BaloonWatcherBundle:Scenario g ORDER BY g.number DESC'
        );

        $scenarioList = $query->getResult();

        //manage the case if no scenarios were already registred
        if ($scenarioList === null) {
            $scenario = new Scenario;
            $scenario->setNumber(0);
        } else if (count($scenarioList) === 0) {
            $scenario = new Scenario;
            $scenario->setNumber(0);
        } else {
            $scenario = $scenarioList[0];
        }
        $scenario->setCreationDate(new \DateTime('now'));
        $scenario->setModificationDate(new \DateTime('now'));

        return $scenario;
    }

    /**
     * @Route("/getGpsRecord/{id}")
     */
    public function getGpsRecordAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $gpsRecord = $em->getRepository('BaloonWatcherBundle:GPSRecord')->find($id);


        return $this->render('BaloonWatcherBundle:Default:index.html.twig',
            array('latitude' => $gpsRecord->getLatitude(),
                'longitude' => $gpsRecord->getLongitude(),
                'altitude' => $gpsRecord->getAltitude()
            )
        );
    }

    /**
     * Returns the list of gpsPoint associated to the last scenario
     * @Route("/gpsPointListForLastScenario", options={"expose"=true})
     */
    public function gpsPointListForLastScenario()
    {
        $em = $this->getDoctrine()->getManager();
        $lastScenario = $this->createNewOrRetrieveLastScenation();
        $gpsRecordList = $lastScenario->getGpsRecordList();

        return new JsonResponse($this->normalizeGpsPointList($gpsRecordList));
    }

    /**
     * This method is used to normalize the gpsPointList (in order to return json data)
     *
     * @param array $gpsPointList
     * return array
     */
    private function normalizeGpsPointList($gpsPointList)
    {
        $res = array();

        foreach ($gpsPointList as $gpsPoint) {
            $normalizedPoint = array();
            $normalizedPoint['latitude'] = $gpsPoint->getLatitude();
            $normalizedPoint['longitude'] = $gpsPoint->getLongitude();
            $normalizedPoint['altitude'] = $gpsPoint->getAltitude();

            $res []= $normalizedPoint;
        }

        return $res;
    }

}