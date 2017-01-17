<?php
/**
 * @package plugins.audit
 * @subpackage api.filters.enum
 */
class BorhanAuditTrailOrderBy extends BorhanStringEnum
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const PARSED_AT_ASC = "+parsedAt";
	const PARSED_AT_DESC = "-parsedAt";
}
