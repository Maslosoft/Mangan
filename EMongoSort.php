<?php

/**
 * @author Ianaré Sévi
 * @author Dariusz Górecki <darek.krk@gmail.com>
 * @author Invenzzia Group, open-source division of CleverIT company http://www.invenzzia.org
 * @copyright 2011 CleverIT http://www.cleverit.com.pl
 * @license New BSD license
 * @version 1.3
 * @category ext
 * @package ext.YiiMongoDbSuite
 */

/**
 * EMongoSort
 * @since v1.3.4
 *
 * Represents information relevant to sorting EMongoDocument dataProviders.
 */
class EMongoSort extends CSort
{
	private $_directions;

	/**
	 * Modifies the query criteria by changing its {@link EMongoRecordDataProvider::order} property.
	 * This method will use {@link directions} to determine which columns need to be sorted.
	 * They will be put in the ORDER BY clause. If the criteria already has non-empty {@link EMongoRecordDataProvider::order} value,
	 * the new value will be appended to it.
	 * @param EMongoRecordDataProvider $criteria the query criteria
	 */
	public function applyOrder($criteria)
	{
		$order = $this->getOrderBy();
		if (!empty($order)) {
			$criteria->setSort($order);
			// todo JOIN this new array properly with existing sort criteria - it just overwrites it now
			//if(!empty($criteria->order))
			//	$criteria->order.=', ';
			//$criteria->order.=$order;
		}
	}

	/**
	 * @return string the order-by columns represented by this sort object.
	 * This can be put in the ORDER BY clause of a SQL statement.
	 * @since 1.1.0
	 */
	public function getOrderBy()
	{
		$directions = $this->getDirections();
		if (empty($directions))
			return is_array($this->defaultOrder) ? $this->defaultOrder : array(); // use the defaultOrder
		else {
			$orders = array();
			foreach ($directions as $attribute => $direction)
				$orders[$attribute] = $direction;

			return $orders;
		}
	}

	/**
	 * Generates a hyperlink that can be clicked to cause sorting.
	 * @param string $attribute the attribute name. This must be the actual attribute name, not alias.
	 * @param string $label the link label. If null, the label will be determined according
	 * to the attribute (see {@link resolveLabel}).
	 * @param array $htmlOptions additional HTML attributes for the hyperlink tag
	 * @return string the generated hyperlink
	 */
	public function link($attribute, $label = null, $htmlOptions = array())
	{
		// todo make sure this works with relations?
		if ($label === null)
			$label = $this->resolveLabel($attribute);
		if (($definition = $this->resolveAttribute($attribute)) === false)
			return $label;
		$directions = $this->getDirections();
		if (isset($directions[$attribute])) {
			$class = ($directions[$attribute] == EMongoCriteria::SORT_DESC) ? 'desc' : 'asc';
			if (isset($htmlOptions['class']))
				$htmlOptions['class'].=' ' . $class;
			else
				$htmlOptions['class'] = $class;
			$direction = $directions[$attribute];
			unset($directions[$attribute]);
		}
		else if (is_array($definition) && isset($definition['default']))
			$direction = $definition['default'];
		else
			$direction = EMongoCriteria::SORT_ASC;

		if ($this->multiSort)
			$directions = array_merge(array($attribute => $direction), $directions);
		else
			$directions = array($attribute => $direction);

		$url = $this->createUrl(Yii::app()->getController(), $directions);

		return $this->createLink($attribute, $label, $url, $htmlOptions);
	}

	/**
	 * Resolves the attribute label for the specified attribute.
	 * This will invoke {@link CActiveRecord::getAttributeLabel} to determine what label to use.
	 * If the attribute refers to a virtual attribute declared in {@link attributes},
	 * then the label given in the {@link attributes} will be returned instead.
	 * @param string $attribute the attribute name.
	 * @return string the attribute label
	 */
	public function resolveLabel($attribute)
	{
		// todo provide support for getAttributeLabel()
		return $attribute;
	}

	/**
	 * Returns the currently requested sort information.
	 * @return array sort directions indexed by attribute names.
	 * The sort direction is true if the corresponding attribute should be
	 * sorted in descending order.
	 */
	public function getDirections()
	{
		if ($this->_directions === null) {
			$this->_directions = array();
			if (isset($_GET[$this->sortVar])) {
				$attributes = explode($this->separators[0], $_GET[$this->sortVar]);
				foreach ($attributes as $attribute)
				{
					if (($pos = strrpos($attribute, $this->separators[1])) !== false)
					{
						$descending = substr($attribute, $pos + 1) === $this->descTag;
						if ($descending) {
							$attribute = substr($attribute, 0, $pos);
							$direction = EMongoCriteria::SORT_DESC;
						}
						else
							$direction = EMongoCriteria::SORT_ASC;
					}
					else
						$direction = EMongoCriteria::SORT_ASC;

					if (($this->resolveAttribute($attribute)) !== false)
					{
						$this->_directions[$attribute] = $direction;
						if (!$this->multiSort)
							return $this->_directions;
					}
				}
			}
			if ($this->_directions === array() && is_array($this->defaultOrder))
				$this->_directions = $this->defaultOrder;
		}
		return $this->_directions;
	}

	/**
	 * Returns the sort direction of the specified attribute in the current request.
	 * @param string $attribute the attribute name
	 * @return mixed the sort direction of the attribute. True if the attribute should be sorted in descending order,
	 * false if in ascending order, and null if the attribute doesn't need to be sorted.
	 */
	public function getDirection($attribute)
	{
		$this->getDirections();
		return isset($this->_directions[$attribute]) ? $this->_directions[$attribute] : null;
	}

	/**
	 * Creates a URL that can lead to generating sorted data.
	 * @param CController $controller the controller that will be used to create the URL.
	 * @param array $directions the sort directions indexed by attribute names.
	 * The sort direction is true if the corresponding attribute should be
	 * sorted in descending order.
	 * @return string the URL for sorting
	 */
	public function createUrl($controller, $directions)
	{
		$sorts = array();
		foreach ($directions as $attribute => $direction)
		{
			if ($direction == EMongoCriteria::SORT_DESC)
				$sorts[] = $attribute;
			else
				$sorts[] = $attribute . $this->separators[1] . $this->descTag;
		}
		$params = $this->params === null ? $_GET : $this->params;
		$params[$this->sortVar] = implode($this->separators[0], $sorts);
		return $controller->createUrl($this->route, $params);
	}

	/**
	 * Returns the real definition of an attribute given its name.
	 *
	 * @param string $attribute the attribute name that the user requests to sort on
	 * @return mixed the attribute name or the virtual attribute definition. False if the attribute cannot be sorted.
	 */
	public function resolveAttribute($attribute)
	{
		// todo flesh this out more so it only works with valid sorting attributes
		return $attribute;
	}

}
