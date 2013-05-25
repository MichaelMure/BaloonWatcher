<?php

namespace lahaut\BaloonWatcherBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use lahaut\BaloonWatcherBundle\Entity\GPSRecord;
use Doctrine\Common\Collections\ArrayCollection;
use lahaut\BaloonWatcherBundle\Entity\ScenarioRepository;

/**
 * Scenario
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ScenarioRepository")
 */
class Scenario
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modificationDate", type="datetime")
     */
    private $modificationDate;

    /**
     * @ORM\OneToMany(targetEntity="GpsRecord", mappedBy="scenario")
     */
    private $gpsRecordList;

    public function __construct()
    {
        $this->gpsRecordList = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return Scenario
     */
    public function setNumber($number)
    {
        $this->number = $number;
    
        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Add gpsRecordList
     *
     * @param GpsRecord $gpsRecordList
     * @return Scenario
     */
    public function addGpsRecordList(\lahaut\BaloonWatcherBundle\Entity\GpsRecord $gpsRecordList)
    {
        $this->gpsRecordList[] = $gpsRecordList;
    
        return $this;
    }

    /**
     * Remove gpsRecordList
     *
     * @param GpsRecord $gpsRecordList
     */
    public function removeGpsRecordList(\lahaut\BaloonWatcherBundle\Entity\GpsRecord $gpsRecordList)
    {
        $this->gpsRecordList->removeElement($gpsRecordList);
    }

    /**
     * Get gpsRecordList
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGpsRecordList()
    {
        return $this->gpsRecordList;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Scenario
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    
        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set modificationDate
     *
     * @param \DateTime $modificationDate
     * @return Scenario
     */
    public function setModificationDate($modificationDate)
    {
        $this->modificationDate = $modificationDate;
    
        return $this;
    }

    /**
     * Get modificationDate
     *
     * @return \DateTime 
     */
    public function getModificationDate()
    {
        return $this->modificationDate;
    }
}