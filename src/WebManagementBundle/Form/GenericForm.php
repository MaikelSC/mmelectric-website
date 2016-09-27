<?php
namespace WebManagementBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenericForm extends AbstractType{
	protected $items;
	protected $entity;
	function __construct($items) {
		$this->items = $items;
		$this->entity = $items['entity'];
	}
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		for($i = 0; $i <count($this->items['fields']) ; $i++){
			if( array_key_exists('options', $this->items)&& $this->items['options'][$i]!= NULL)
			{
				$builder->add($this->items['fields'][$i], $this->items['fields_types'][$i], $this->items['options'][$i]);
				
			}
			else
			$builder->add($this->items['fields'][$i], $this->items['fields_types'][$i]);			
		}
		/*dump($builder);
    die;*/		
	}
	
	public function configureOptions(OptionsResolver $resolver)
    {	
    /*dump($this->entity);
    die;*/
        $resolver->setDefaults(array(
            'data_class' => 'WebManagementBundle\Entity'.$this->entity
        ));
    }

    public function getName()
    {    	
        return stripslashes($this->entity);
    }

}
?>