<?php

namespace Maslosoft\Mangan\File\Sender;

use Maslosoft\Mangan\Interfaces\File\WrapperInterface;

abstract class Base
{
	protected function withHeaders(WrapperInterface $wrapper): void
	{
		$meta = (object)$wrapper->getMetadata();
		header(sprintf('Content-Length: %d', $wrapper->getLength()));
		header(sprintf('Content-Type: %s', $meta->contentType));
		header(sprintf('ETag: %s', $meta->md5));
		header(sprintf('Last-Modified: %s', gmdate('D, d M Y H:i:s \G\M\T', $meta->uploadDate->sec)));
		header(sprintf('Content-Disposition: filename="%s"', basename($meta->filename)));
		// Cache it
		header('Pragma: public');
		header('Cache-Control: max-age=86400');
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
	}
}