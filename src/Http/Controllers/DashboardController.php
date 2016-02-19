<?php namespace Mirage\Admin\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
/**
 * Description of Dashboard
 *
 * @author Bryan Salazar
 */
class DashboardController extends BaseController
{
	public function index()
	{
		return view('dashboard.index');
	}
}
