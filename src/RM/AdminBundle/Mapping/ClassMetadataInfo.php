<?php

namespace RM\AdminBundle\Mapping;

use InvalidArgumentException;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;

class ClassMetadataInfo implements ClassMetadata {

    const INHERITANCE_TYPE_NONE = 1;

    const INHERITANCE_TYPE_SINGLE_COLLECTION = 2;

    const INHERITANCE_TYPE_COLLECTION_PER_CLASS = 3;

    public $identifier;

    public $name;

    private $_inMenu = false;
    private $_menuName;

    public $namespace;

    public $rootDocumentName;

    public $parentClasses = array();

    public $subClasses = array();

    public $reflFields = array();

    public $inheritanceType = self::INHERITANCE_TYPE_NONE;


    /**
     * READ-ONLY: The field mappings of the class.
     * Keys are field names and values are mapping definitions.
     *
     * The mapping definition array has the following values:
     *
     * - <b>fieldName</b> (string)
     * The name of the field in the Document.
     *
     * - <b>id</b> (boolean, optional)
     * Marks the field as the primary key of the document. Multiple fields of an
     * document can have the id attribute, forming a composite key.
     *
     * @var array
     */
    public $fieldMappings = array();

    /**
     * READ-ONLY: The association mappings of the class.
     * Keys are field names and values are mapping definitions.
     *
     * @var array
     */
    public $associationMappings = array();


    /**
     * READ-ONLY: Whether this class describes the mapping of a mapped superclass.
     *
     * @var boolean
     */
    public $isMappedSuperclass = false;

    /**
     * READ-ONLY: Whether this class describes the mapping of a embedded document.
     *
     * @var boolean
     */
    public $isEmbeddedDocument = false;

    /**
     * The ReflectionClass instance of the mapped class.
     *
     * @var ReflectionClass
     */
    public $reflClass;

    /**
     * Initializes a new ClassMetadata instance that will hold the object-document mapping
     * metadata of the class with the given name.
     *
     * @param string $documentName The name of the document class the new instance is used for.
     */
    public function mapFieldnstruct($documentName)
    {
        $this->name = $documentName;
        $this->rootDocumentName = $documentName;
    }

    /**
     * Gets the ReflectionClass instance of the mapped class.
     *
     * @return ReflectionClass
     */
    public function getReflectionClass()
    {
        if ( ! $this->reflClass) {
            $this->reflClass = new \ReflectionClass($this->name);
        }
        return $this->reflClass;
    }

    /**
     * Checks whether a field is part of the identifier/primary key field(s).
     *
     * @param string $fieldName  The field name
     * @return boolean  TRUE if the field is part of the table identifier/primary key field(s),
     *                  FALSE otherwise.
     */
    public function isIdentifier($fieldName)
    {
        return $this->identifier === $fieldName ? true : false;
    }

    /**
     * INTERNAL:
     * Sets the mapped identifier field of this class.
     *
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * Gets the mapped identifier field of this class.
     *
     * @return string $identifier
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Get identifier field names of this class.
     *
     * Since MongoDB only allows exactly one identifier field this is a proxy
     * to {@see getIdentifier()} and returns an array.
     *
     * @return array
     */
    public function getIdentifierFieldNames()
    {
        return array($this->identifier);
    }

    /**
     * Checks whether the class has a (mapped) field with a certain name.
     *
     * @return boolean
     */
    public function hasField($fieldName)
    {
        return isset($this->fieldMappings[$fieldName]);
    }

    /**
     * Sets the inheritance type used by the class and it's subclasses.
     *
     * @param integer $type
     */
    public function setInheritanceType($type)
    {
        $this->inheritanceType = $type;
    }

    /**
     * Checks whether a mapped field is inherited from an entity superclass.
     *
     * @return boolean TRUE if the field is inherited, FALSE otherwise.
     */
    public function isInheritedField($fieldName)
    {
        return isset($this->fieldMappings[$fieldName]['inherited']);
    }


    /**

    /**
     * Gets the ReflectionProperties of the mapped class.
     *
     * @return array An array of ReflectionProperty instances.
     */
    public function getReflectionProperties()
    {
        return $this->reflFields;
    }

    /**
     * Gets a ReflectionProperty for a specific field of the mapped class.
     *
     * @param string $name
     * @return ReflectionProperty
     */
    public function getReflectionProperty($name)
    {
        return $this->reflFields[$name];
    }

    /**
     * The name of this Document class.
     *
     * @return string $name The Document class name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The namespace this Document class belongs to.
     *
     * @return string $namespace The namespace name.
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Map a field.
     *
     * @param array $mapping The mapping information.
     */
    public function mapField(array $mapping)
    {
        if ( ! isset($mapping['fieldName']) && isset($mapping['name'])) {
            $mapping['fieldName'] = $mapping['name'];
        }
        if ( ! isset($mapping['fieldName'])) {
            throw MappingException::missingFieldName($this->name);
        }
        if ( ! isset($mapping['name'])) {
            $mapping['name'] = $mapping['fieldName'];
        }
        if (isset($this->fieldMappings[$mapping['fieldName']])) {
            //throw MappingException::duplicateFieldMapping($this->name, $mapping['fieldName']);
        }
        if (isset($mapping['targetDocument']) && strpos($mapping['targetDocument'], '\\') === false && strlen($this->namespace)) {
            $mapping['targetDocument'] = $this->namespace . '\\' . $mapping['targetDocument'];
        }


        if (isset($mapping['type']) && $mapping['type'] === 'file') {
            $mapping['file'] = true;
        }


        $mapping['isOwningSide'] = true;
        $mapping['isInverseSide'] = false;
        if (isset($mapping['reference'])) {
            if (isset($mapping['inversedBy']) && $mapping['inversedBy']) {
                $mapping['isOwningSide'] = true;
                $mapping['isInverseSide'] = false;
            }
            if (isset($mapping['mappedBy']) && $mapping['mappedBy']) {
                $mapping['isInverseSide'] = true;
                $mapping['isOwningSide'] = false;
            }
        }

        $this->fieldMappings[$mapping['fieldName']] = $mapping;
        if (isset($mapping['association'])) {
            $this->associationMappings[$mapping['fieldName']] = $mapping;
        }

        return $mapping;
    }

    /**
     * Map a MongoGridFSFile.
     *
     * @param array $mapping The mapping information.
     */
    public function mapFile(array $mapping)
    {
        $mapping['file'] = true;
        $mapping['type'] = 'file';
        $this->mapField($mapping);
    }

    /**
     * Map a single embedded document.
     *
     * @param array $mapping The mapping information.
     */
    public function mapOneEmbedded(array $mapping)
    {
        $mapping['embedded'] = true;
        $mapping['type'] = 'one';
        $this->mapField($mapping);
    }

    /**
     * Map a collection of embedded documents.
     *
     * @param array $mapping The mapping information.
     */
    public function mapManyEmbedded(array $mapping)
    {
        $mapping['embedded'] = true;
        $mapping['type'] = 'many';
        $this->mapField($mapping);
    }

    /**
     * Map a single document reference.
     *
     * @param array $mapping The mapping information.
     */
    public function mapOneReference(array $mapping)
    {
        $mapping['reference'] = true;
        $mapping['type'] = 'one';
        $this->mapField($mapping);
    }

    /**
     * Map a collection of document references.
     *
     * @param array $mapping The mapping information.
     */
    public function mapManyReference(array $mapping)
    {
        $mapping['reference'] = true;
        $mapping['type'] = 'many';
        $this->mapField($mapping);
    }

    /**
     * INTERNAL:
     * Adds a field mapping without completing/validating it.
     * This is mainly used to add inherited field mappings to derived classes.
     *
     * @param array $mapping
     */
    public function addInheritedFieldMapping(array $fieldMapping)
    {
        $this->fieldMappings[$fieldMapping['fieldName']] = $fieldMapping;
    }

    /**
     * Checks whether the class has a mapped association with the given field name.
     *
     * @param string $fieldName
     * @return boolean
     */
    public function hasReference($fieldName)
    {
        return isset($this->fieldMappings[$fieldName]['reference']);
    }

    /**
     * Checks whether the class has a mapped embed with the given field name.
     *
     * @param string $fieldName
     * @return boolean
     */
    public function hasEmbed($fieldName)
    {
        return isset($this->fieldMappings[$fieldName]['embedded']);
    }

    /**
     * Checks whether the class has a mapped association (embed or reference) with the given field name.
     *
     * @param string $fieldName
     * @return boolean
     */
    public function hasAssociation($fieldName)
    {
        return $this->hasReference($fieldName) || $this->hasEmbed($fieldName);
    }

    /**
     * Checks whether the class has a mapped reference or embed for the specified field and
     * is a single valued association.
     *
     * @param string $fieldName
     * @return boolean TRUE if the association exists and is single-valued, FALSE otherwise.
     */
    public function isSingleValuedAssociation($fieldName)
    {
        return $this->isSingleValuedReference($fieldName) || $this->isSingleValuedEmbed($fieldName);
    }

    /**
     * Checks whether the class has a mapped reference or embed for the specified field and
     * is a collection valued association.
     *
     * @param string $fieldName
     * @return boolean TRUE if the association exists and is collection-valued, FALSE otherwise.
     */
    public function isCollectionValuedAssociation($fieldName)
    {
        return $this->isCollectionValuedReference($fieldName) || $this->isCollectionValuedEmbed($fieldName);
    }

    /**
     * Checks whether the class has a mapped association for the specified field
     * and if yes, checks whether it is a single-valued association (to-one).
     *
     * @param string $fieldName
     * @return boolean TRUE if the association exists and is single-valued, FALSE otherwise.
     */
    public function isSingleValuedReference($fieldName)
    {
        return isset($this->fieldMappings[$fieldName]['association']) &&
            $this->fieldMappings[$fieldName]['association'] === self::REFERENCE_ONE;
    }

    /**
     * Checks whether the class has a mapped association for the specified field
     * and if yes, checks whether it is a collection-valued association (to-many).
     *
     * @param string $fieldName
     * @return boolean TRUE if the association exists and is collection-valued, FALSE otherwise.
     */
    public function isCollectionValuedReference($fieldName)
    {
        return isset($this->fieldMappings[$fieldName]['association']) &&
            $this->fieldMappings[$fieldName]['association'] === self::REFERENCE_MANY;
    }

    /**
     * Checks whether the class has a mapped embedded document for the specified field
     * and if yes, checks whether it is a single-valued association (to-one).
     *
     * @param string $fieldName
     * @return boolean TRUE if the association exists and is single-valued, FALSE otherwise.
     */
    public function isSingleValuedEmbed($fieldName)
    {
        return isset($this->fieldMappings[$fieldName]['association']) &&
            $this->fieldMappings[$fieldName]['association'] === self::EMBED_ONE;
    }

    /**
     * Checks whether the class has a mapped embedded document for the specified field
     * and if yes, checks whether it is a collection-valued association (to-many).
     *
     * @param string $fieldName
     * @return boolean TRUE if the association exists and is collection-valued, FALSE otherwise.
     */
    public function isCollectionValuedEmbed($fieldName)
    {
        return isset($this->fieldMappings[$fieldName]['association']) &&
            $this->fieldMappings[$fieldName]['association'] === self::EMBED_MANY;
    }

    /**
     * Sets the ID generator used to generate IDs for instances of this class.
     *
     * @param AbstractIdGenerator $generator
     */
    public function setIdGenerator($generator)
    {
        $this->idGenerator = $generator;
    }

    /**
     * Casts the identifier to its portable PHP type.
     *
     * @param mixed $id
     * @return mixed $id
     */
    public function getPHPIdentifierValue($id)
    {
        $idType = $this->fieldMappings[$this->identifier]['type'];
        return Types\Type::getType($idType)->convertToPHPValue($id);
    }

    /**
     * Casts the identifier to its database type.
     *
     * @param mixed $id
     * @return mixed $id
     */
    public function getDatabaseIdentifierValue($id)
    {
        $idType = $this->fieldMappings[$this->identifier]['type'];
        return Types\Type::getType($idType)->convertToDatabaseValue($id);
    }

    /**
     * Sets the document identifier of a document.
     *
     * @param object $document
     * @param mixed $id
     */
    public function setIdentifierValue($document, $id)
    {
        $id = $this->getPHPIdentifierValue($id);
        $this->reflFields[$this->identifier]->setValue($document, $id);
    }

    /**
     * Gets the document identifier.
     *
     * @param object $document
     * @return string $id
     */
    public function getIdentifierValue($document)
    {
        if ($document instanceof Proxy && !$document->__isInitialized()) {
            return $document->__identifier__;
        }
        return $this->reflFields[$this->identifier]->getValue($document);
    }

    /**
     * Get identifier values of this document.
     *
     * Since MongoDB only allows exactly one identifier field this is a proxy
     * to {@see getIdentifierValue()} and returns an array with the identifier
     * field as a key.
     *
     * @param object $document
     * @return array
     */
    public function getIdentifierValues($document)
    {
        return array($this->identifier => $this->getIdentifierValue($document));
    }

    /**
     * Sets the specified field to the specified value on the given document.
     *
     * @param object $document
     * @param string $field
     * @param mixed $value
     */
    public function setFieldValue($document, $field, $value)
    {
        $this->reflFields[$field]->setValue($document, $value);
    }

    /**
     * Gets the specified field's value off the given document.
     *
     * @param object $document
     * @param string $field
     */
    public function getFieldValue($document, $field)
    {
        if ($document instanceof Proxy && $field === $this->identifier && !$document->__isInitialized()) {
            return $document->__identifier__;
        }
        return $this->reflFields[$field]->getValue($document);
    }

    /**
     * Gets the mapping of a field.
     *
     * @param string $fieldName  The field name.
     * @return array  The field mapping.
     */
    public function getFieldMapping($fieldName)
    {
        if ( ! isset($this->fieldMappings[$fieldName])) {
            throw MappingException::mappingNotFound($this->name, $fieldName);
        }
        return $this->fieldMappings[$fieldName];
    }

    /**
     * Check if the field is not null.
     *
     * @param string $fieldName  The field name
     * @return boolean  TRUE if the field is not null, FALSE otherwise.
     */
    public function isNullable($fieldName)
    {
        $mapping = $this->getFieldMapping($fieldName);
        if ($mapping !== false) {
            return isset($mapping['nullable']) && $mapping['nullable'] == true;
        }
        return false;
    }

    /**
     * Sets the type of Id generator to use for the mapped class.
     */
    public function setIdGeneratorType($generatorType)
    {
        $this->generatorType = $generatorType;
    }

    /**
     * Sets the Id generator options.
     */
    public function setIdGeneratorOptions($generatorOptions)
    {
        $this->generatorOptions = $generatorOptions;
    }

    /**
     * @return boolean
     */
    public function isInheritanceTypeNone() {
        return $this->inheritanceType == self::INHERITANCE_TYPE_NONE;
    }

    /**
     * Checks whether the mapped class uses the SINGLE_COLLECTION inheritance mapping strategy.
     *
     * @return boolean TRUE if the class participates in a SINGLE_COLLECTION inheritance mapping,
     *                 FALSE otherwise.
     */
    public function isInheritanceTypeSingleCollection() {
        return $this->inheritanceType == self::INHERITANCE_TYPE_SINGLE_COLLECTION;
    }

    /**
     * Checks whether the mapped class uses the COLLECTION_PER_CLASS inheritance mapping strategy.
     *
     * @return boolean TRUE if the class participates in a COLLECTION_PER_CLASS inheritance mapping,
     *                 FALSE otherwise.
     */
    public function isInheritanceTypeCollectionPerClass() {
        return $this->inheritanceType == self::INHERITANCE_TYPE_COLLECTION_PER_CLASS;
    }

    /**
     * Sets the mapped subclasses of this class.
     * @param array $subclasses The names of all mapped subclasses.
     */
    public function setSubclasses(array $subclasses) {
        foreach ($subclasses as $subclass) {
            if (strpos($subclass, '\\') === false && strlen($this->namespace)) {
                $this->subClasses[] = $this->namespace . '\\' . $subclass;
            } else {
                $this->subClasses[] = $subclass;
            }
        }
    }

    /**
     * Sets the parent class names.
     * Assumes that the class names in the passed array are in the order:
     * directParent -> directParentParent -> directParentParentParent ... -> root.
     */
    public function setParentClasses(array $classNames) {
        $this->parentClasses = $classNames;
        if (count($classNames) > 0) {
            $this->rootDocumentName = array_pop($classNames);
        }
    }

    /**
     * A numerically indexed list of field names of this persistent class.
     * This array includes identifier fields if present on this class.
     * @return array
     */
    public function getFieldNames() {
        return array_keys($this->fieldMappings);
    }

    public function getAssociationNames() {
        return array_keys($this->associationMappings);
    }

    /**
     * Gets the type of a field.
     *
     * @param string $fieldName
     * @return Doctrine\DBAL\Types\Type
     */
    public function getTypeOfField($fieldName) {
        return isset($this->fieldMappings[$fieldName]) ?
            $this->fieldMappings[$fieldName]['type'] : null;
    }

    public function getAssociationTargetClass($assocName) {
        if ( ! isset($this->associationMappings[$assocName])) {
            throw new InvalidArgumentException("Association name expected, '" . $assocName ."' is not an association.");
        }
        return $this->associationMappings[$assocName]['targetDocument'];
    }

    public function isAssociationInverseSide($fieldName) {
        throw new \BadMethodCallException(__METHOD__.'() is not implemented yet.');
    }

    public function getAssociationMappedByTargetField($fieldName) {
        throw new \BadMethodCallException(__METHOD__.'() is not implemented yet.');
    }

    public function inMenu($status = null) {
        if (!is_null($status)) {
            $this->_inMenu = (bool)$status;
        }
        return $this->_inMenu;
    }

    public function setMenuName($menuName) {
        $this->_menuName = $menuName;
    }

    public function getMenuName() {
        return $this->_menuName;
    }

}
