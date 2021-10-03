
namespace myPHPnotes\Microsoft\Models;

use GuzzleHttp\Exception\ClientException;
use Microsoft\Graph\Model\Calendar as MicrosoftCalendar;


/**
 * Calendar Model
 */
class Calendar extends BaseModel
{
    public $data;
    function __construct()
    {
        $this->checkAuthentication();
        $this->fetch();
    }
    protected function fetch()
    {
        $url =  "/me/calendars";
        try {
            $calendars = $this->graph()->createRequest("get",$url)
                ->setReturnType(MicrosoftCalendar::class)
                ->execute();
        } catch (ClientException $e) {
            throw new \Exception("Cannot connect make sure you have asked User.Read permission from the authenticated user.", 1);
            return false;

        }
        $this->data = $calendars;
        return $this->data;;
    }
}
