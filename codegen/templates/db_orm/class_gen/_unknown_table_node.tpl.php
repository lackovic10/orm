<?php
    /** @var QSqlTable $objTable */
    /** @var \QCubed\Codegen\DatabaseCodeGen $objCodeGen */
    global $_TEMPLATE_SETTINGS;
    $_TEMPLATE_SETTINGS = array(
        'OverwriteFlag' => true,
        'DirectorySuffix' => '',
        'TargetDirectory' => QCUBED_PROJECT_MODEL_GEN_DIR,
        'TargetFileName' => 'Node'.$objTable->ClassName.'Unknown.php'
    );
?>
<?php print("<?php\n"); ?>

namespace <?php echo QCUBED_PROJECT_MODEL_GEN_NAMESPACE; ?>;

<?php foreach ($objTable->ManyToManyReferenceArray as $objReference) { ?>
/**
 * @property-read Node\Column $<?= $objReference->OppositePropertyName ?>

 * @property-read Node<?= $objReference->VariableType ?> $<?= $objReference->VariableType ?>

 * @property-read Node<?= $objReference->VariableType ?> $_ChildTableNode
 **/
class Node<?= $objTable->ClassName ?><?= $objReference->ObjectDescription ?> extends Node\Association
{
    protected $strType = \QCubed\Type::ASSOCIATION;
    protected $strName = '<?= strtolower($objReference->ObjectDescription); ?>';

    protected $strTableName = '<?= $objReference->Table ?>';
    protected $strPrimaryKey = '<?= $objReference->Column ?>';
    protected $strClassName = '<?= $objReference->VariableType ?>';
    protected $strPropertyName = '<?= $objReference->ObjectDescription ?>';
    protected $strAlias = '<?= strtolower($objReference->ObjectDescription); ?>';

    /**
    * __get Magic Method
    *
    * @param string $strName
    * @throws Caller
    */
    public function __get($strName) {
        switch ($strName) {
            case '<?= $objReference->OppositePropertyName ?>':
                return new Node\Column('<?= $objReference->OppositeColumn ?>', '<?= $objReference->OppositePropertyName ?>', '<?= $objReference->OppositeDbType ?>', $this);
            case '<?= $objReference->VariableType ?>':
                return new Node<?= $objReference->VariableType ?>('<?= $objReference->OppositeColumn ?>', '<?= $objReference->OppositePropertyName ?>', '<?= $objReference->OppositeDbType ?>', $this);
            case '_ChildTableNode':
                return new Node<?= $objReference->VariableType ?>('<?= $objReference->OppositeColumn ?>', '<?= $objReference->OppositePropertyName ?>', '<?= $objReference->OppositeDbType ?>', $this);
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

<?php } ?>
