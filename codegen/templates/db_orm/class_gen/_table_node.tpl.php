<?php
    /** @var QSqlTable $objTable */
    /** @var \QCubed\Codegen\DatabaseCodeGen $objCodeGen */
    global $_TEMPLATE_SETTINGS;
    $_TEMPLATE_SETTINGS = array(
        'OverwriteFlag' => true,
        'DirectorySuffix' => '',
        'TargetDirectory' => QCUBED_PROJECT_MODEL_GEN_DIR,
        'TargetFileName' => 'Node'.$objTable->ClassName.'.php'
    );
?>
<?php print("<?php\n"); ?>

namespace <?php echo QCUBED_PROJECT_MODEL_GEN_NAMESPACE; ?>;

use QCubed\Query\Node;

/**
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
 * @property-read Node\Column $<?= $objColumn->PropertyName ?>

<?php if ($objColumn->Reference) { ?>
 * @property-read Node<?= $objColumn->Reference->VariableType; ?> $<?= $objColumn->Reference->PropertyName ?>

<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
 * @property-read Node<?= $objTable->ClassName ?><?= $objReference->ObjectDescription ?> $<?= $objReference->ObjectDescription ?>

<?php } ?>
<?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?>
 * @property-read ReverseReferenceNode<?= $objReference->VariableType ?> $<?= $objReference->ObjectDescription ?>

<?php } ?>
<?php $objPkColumn = $objTable->PrimaryKeyColumnArray[0]; ?>
 * @property-read Node\Column<?php if (($objPkColumn->Reference) && (!$objPkColumn->Reference->IsType)) print $objPkColumn->Reference->VariableType; ?> $_PrimaryKeyNode
 **/
class Node<?= $objTable->ClassName ?> extends Node\Table {
    protected $strTableName = '<?= $objTable->Name ?>';
    protected $strPrimaryKey = '<?= $objTable->PrimaryKeyColumnArray[0]->Name ?>';
    protected $strClassName = '<?= $objTable->ClassName ?>';

    /**
    * @return array
    */
    public function fields() {
        return [
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
            "<?= $objColumn->Name ?>",
<?php } ?>
        ];
    }

    /**
    * @return array
    */
    public function primaryKeyFields() {
        return [
<?php foreach ($objTable->PrimaryKeyColumnArray as $objColumn) { ?>
            "<?= $objColumn->Name ?>",
<?php } ?>
        ];
    }

   /**
    * @return AbstractDatabase
    */
    protected function database() {
        return \QCubed\Database\Service::getDatabase(<?= $objCodeGen->DatabaseIndex; ?>);
    }


    /**
    * __get Magic Method
    *
    * @param string $strName
    * @throws Caller
    */
    public function __get($strName) {
        switch ($strName) {
<?php foreach ($objTable->ColumnArray as $objColumn) { ?>
            case '<?= $objColumn->PropertyName ?>':
                return new Node\Column('<?= $objColumn->Name ?>', '<?= $objColumn->PropertyName ?>', '<?= $objColumn->DbType ?>', $this);
<?php if ($objColumn->Reference) { ?>
            case '<?= $objColumn->Reference->PropertyName ?>':
                return new Node<?= $objColumn->Reference->VariableType; ?>('<?= $objColumn->Name ?>', '<?= $objColumn->Reference->PropertyName ?>', '<?= $objColumn->DbType ?>', $this);
<?php } ?>
<?php } ?>
<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
            case '<?= $objReference->ObjectDescription ?>':
                return new Node<?= $objTable->ClassName ?><?= $objReference->ObjectDescription ?>($this);
<?php } ?><?php foreach ($objTable->ReverseReferenceArray as $objReference) { ?>
            case '<?= $objReference->ObjectDescription ?>':
                return new ReverseReferenceNode<?= $objReference->VariableType ?>($this, '<?= strtolower($objReference->ObjectDescription); ?>', \QCubed\Type::REVERSE_REFERENCE, '<?= $objReference->Column ?>', '<?= $objReference->ObjectDescription ?>');
<?php } ?><?php $objPkColumn = $objTable->PrimaryKeyColumnArray[0]; ?>

            case '_PrimaryKeyNode':
<?php if (($objPkColumn->Reference) && (!$objPkColumn->Reference->IsType)) {?>
                return new Node<?= $objPkColumn->Reference->VariableType; ?>('<?= $objPkColumn->Name ?>', '<?= $objPkColumn->PropertyName ?>', '<?= $objPkColumn->DbType ?>', $this);
<?php } else { ?>
                return new Node\Column('<?= $objPkColumn->Name ?>', '<?= $objPkColumn->PropertyName ?>', '<?= $objPkColumn->DbType ?>', $this);
<?php } ?>
            default:
                try {
                    return parent::__get($strName);
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }
}

