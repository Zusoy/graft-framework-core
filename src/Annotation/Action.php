<?php

namespace Graft\Framework\Annotation;

use Graft\Framework\Common\AbstractAnnotation;
use Doctrine\Common\Annotations\Annotation;

/**
 * WordPress Action Annotation
 * 
 * @Annotation()
 * @Target("METHOD")
 * 
 * @package  Graft/Annotation
 * @category Annotation
 * @author   Zusoy <gregoire.drapeau79@gmail.com>
 * @license  MIT
 * @since    0.0.3
 */
class Action extends AbstractAnnotation
{
    /**
     * Action Name
     * 
     * @Required()
     *
     * @var string
     */
    public $name;

    /**
     * Action Priority
     *
     * @var integer
     */
    public $priority;

    /**
     * Action Accepted Params
     *
     * @var integer
     */
    public $params;


    /**
     * Action Annotation Constructor
     * 
     * @param array $values Annotation Parameters Values
     */
    public function __construct(array $values)
    {
        $this->name = $values['name'];

        $this->priority = (isset($values['priority']))
            ? $values['priority']
            : 10;
        
        $this->params = (isset($values['params']))
            ? $values['params']
            : 1;
    }


    /**
     * Annotation Action
     *
     * @return void
     */
    public function action()
    {
        
    }
}