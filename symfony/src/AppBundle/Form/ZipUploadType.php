<?php

namespace AppBundle\Form;

use AppBundle\Entity\ZipUpload;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ZipUploadType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file',FileType::class, array('required' => false, 'attr' => array('class' => 'inputfileCustomDragDrop','id' => 'zip_upload_file')))
            ->add('save', SubmitType::class, array('label' => 'Process It ', 'attr' => array('class' => 'disabledProcessButton btn btn-sm btn-primary','disabled' =>true)))
            ->add('showResult', ButtonType::class, array('label' => 'Show last result', 'attr' => array('class' => 'd-none btn btn-success','data-toggle' => 'modal', 'data-target' => '#popupModalMessage')));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ZipUpload::class,
            'attr' => ['id' => 'js-upload-form']
        ));
    }


}