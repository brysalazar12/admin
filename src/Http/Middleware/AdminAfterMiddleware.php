<?php namespace Mirage\Admin\Http\Middleware;

use Mirage\Admin\Events\AdminAfterEvent;
use Closure;
use App\Http\Middleware\Authenticate;

/**
 * Description of AdminAfterMiddleware
 *
 * @author Bryan Salazar
 */
class AdminAfterMiddleware
{
	public function handle($request, Closure $next)
	{
		$response = $next($request);

		event('admin.after', new AdminAfterEvent());

		return $response;
	}
}
