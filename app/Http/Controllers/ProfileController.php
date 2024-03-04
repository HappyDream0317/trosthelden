<?php

namespace App\Http\Controllers;

use App\Group;
use App\Helpers\DeadReasonHelper;
use App\MatchingUserAnswer;
use App\ProfileMotto;
use App\ProfileQuestion;
use App\ProfileQuestionAnswer;
use App\SendinBlue\SendinBlueApi;
use App\SendinBlue\SendinBlueHandler;
use App\SendinBlue\SendinBlueTracker;
use App\Tooltip;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    const GENDER_QUESTION_ID = 7;
    const GENDER_ANSWER_ID_MALE = 80;
    const GENDER_ANSWER_ID_FEMALE = 81;
    const GENDER_ANSWER_ID_DIVERSE = 82;

    const PLACE_OF_LIVING_QUESTION_ID = 9;
    const POSTAL_QUESTION_ID = 10;

    const MARITAL_QUESTION_ID = 12;

    const BIRTHDAY_QUESTION_ID = 8;

    const JOB_QUESTION_ID = 31;

    const JOB_NAME_ID = 191;
    const JOB_TYPE_ID = 30;
    const RELIGION_QUESTION_ID = 32;

    const NUMBER_OF_BIOLOGICAL_CHILDREN_QUESTION_ID = 15;
    const NUMBER_OF_ADOPTED_CHILDREN_QUESTION_ID = 16;
    const NUMBER_OF_STEP_CHILDREN_QUESTION_ID = 17;
    const NUMBER_OF_FOSTER_CHILDREN_QUESTION_ID = 18;
    const CHILDREN_IN_HOUSEHOLD_QUESTION_ID = 20;

    const ARE_CHILDREN_AFFECTED_QUESTION_IDS = [23,24,25,26,27];
    const ARE_CHILDREN_AFFECTED_QUESTION_ID_1 = 23;
    const ARE_CHILDREN_AFFECTED_QUESTION_ID_2 = 25;
    const ARE_CHILDREN_AFFECTED_QUESTION_ID_3 = 27;

    const REASON_OF_DEATH_QUESTION_ID = 5;
    const DATE_OF_DEATH_QUESTION_ID = 6;
    const RELATION_TO_DECEASED_QUESTION_ID = 2;

    public $avatar;

    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function saveMotto(Request $request)
    {
        $motto = ProfileMotto::where('user_id', Auth::id())->get()->first();
        $text = $request->motto;

        if ($text) {
            $motto->text = $text;
        } else {
            $motto->text = '';
        }

        if ($motto->update()) {

            $this->eventProfileSloganFilledOut(Auth::user());            

            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function getForeignProfileInit($user_id)
    {
        $user = User::where('id', $user_id)->first();
        if (! $user->isInitialized()) {
            User::initProfile($user->id);
        }

        $this->eventForeignProfileViewed(Auth::user());

        return response()->json([
            'user' => $this->getUser($user),
            'profile_questions' => $this->getProfileQuestions($user_id),
            'reason' => $this->getReason($user),
            'mourning' => $this->getMourning($user),
            'info' => $this->getForeignUserInfo($user_id),
            'groups' => $this->getGroups($user_id)
         ]);
    }

    public function getProfileInit()
    {
        $user = Auth::user();

        if (! $user->isInitialized()) {
            User::initProfile($user->id);
        }

        $profile = [
            'motto'             => $this->getMotto($user->id),
            'profile_questions' => $this->getProfileQuestions($user->id),
            'tooltips'          => $this->getProfileTooltips(),
            'reason'            => $this->getReason($user),
            'mourning'          => $this->getMourning($user),
            'user'              => $this->getUser($user),
            'info'              => $this->getUserInfo($user->id),
            'visibilities'      => $this->getVisibilities($user->id),
            'avatar'            => $user->avatar,
            'groups'            => $this->getGroups($user->id)
        ];

        return response()->json($profile);
    }

    /**
     * For mapping purposes.
     * Maybe not all information should be send to the frontend.
     *
     * @param User $user
     * @return User
     */

    public function getUser(User $user)
    {
        $user->is_premium = $user->force_premium ? true : (bool) $user->is_premium;
        unset($user->force_premium);

        $user->permissionsViaRoles = $user->getPermissionsViaRoles()->pluck('name');

        return $user;
    }

    public function storeLastSeen()
    {
        $user = Auth::user();
        $user->last_seen_at = Carbon::now();
        $user->save();
        return response([]);
    }

    private function getForeignUserInfo($user_id)
    {
        $info_array = [
            'country' => $this->getPlaceOfLiving($user_id),
            'postal' => $this->getPostal($user_id),
            'marital_status' => $this->getMaritalStatus($user_id),
        ];

        $visibility = $this->getVisibilities($user_id);

        if ($visibility['children']) {
            $info_array['number_of_children'] = $this->getNumberOfChildren($user_id);
            $info_array['children_in_household'] = $this->getChildrenInHousehold($user_id);
            $info_array['children_affected'] = $this->getAreChildrenAffected($user_id);
        } else {
            $info_array['number_of_children'] = '';
            $info_array['children_in_household'] = '';
            $info_array['children_affected'] = '';
        }

        if ($visibility['job']) {
            $info_array['job'] = $this->getJob($user_id);
        } else {
            $info_array['job'] = '';
        }

        if ($visibility['religion']) {
            $info_array['religion'] = $this->getReligion($user_id);
        } else {
            $info_array['religion'] = '';
        }

        return $info_array;
    }

    private function getGroups($user_id)
    {
        return Group::whereHas('user', function ($q) use ($user_id) {
            $q->where('user_id', $user_id);
        })->get();
    }

    private function getMotto($user_id)
    {
        return ProfileMotto::where('user_id', $user_id)->get()->first()->text;
    }

    public function isUserBlocked($foreign_user_id)
    {
        $user_id = Auth::id();

        $return = DB::table('user_blocklist')->where([
            ['user_id', $foreign_user_id],
            ['blocked_user_id', $user_id],
        ])->get()->first();

        if ($return) {
            return true;
        }

        return false;
    }

    private function getProfileQuestions($user_id): array
    {
        return ProfileQuestion::where('active', '=', true)
          ->with(['answers' => function (HasMany $a) use ($user_id) {
              $a->where('user_id', $user_id);
          }])->get((['id', 'position', 'text']))->map(function (ProfileQuestion $question_with_answer) {
              $answer = $question_with_answer->answers->first();

              return [
                'id' => $question_with_answer->id,
                'position' => $question_with_answer->position,
                'question' => $question_with_answer->text,
                'answer' => $answer ? $answer->answer_text : null,
            ];
          })->toArray();
    }

    private function getProfileTooltips()
    {
        $return_tooltips = [];

        $tooltips = Tooltip::where('page', 'profile')->get();

        foreach ($tooltips as $tooltip) {
            $return_tooltips[$tooltip->component] = $tooltip->text;
        }

        return $return_tooltips;
    }

    private function getUserInfo($user_id)
    {
        return [
            'country' => $this->getPlaceOfLiving($user_id),
            'postal' => $this->getPostal($user_id),
            'number_of_children' => $this->getNumberOfChildren($user_id),
            'children_in_household' => $this->getChildrenInHousehold($user_id),
            'children_affected' => $this->getAreChildrenAffected($user_id),
            'religion' => $this->getReligion($user_id),
            'marital_status' => $this->getMaritalStatus($user_id),
            'job' => $this->getJob($user_id),
        ];
    }

    private function getReason(User $user)
    {
        return [
            'death_reason' => $user->reasonOfDeath(),
            'death_date' => $user->dateOfDeath(),
            'death_relation' => $user->relationToDeceased(),
        ];
    }

    private function getMourning(User $user)
    {
        return [
            'code' => $user->mourning_code,
            'gender' => $user->mourning_gender,
            'text' => $user->mourning_text
        ];
    }

    private function getPlaceOfLiving($user_id)
    {
        $return = MatchingUserAnswer::where('user_id', $user_id)->whereHas('answer', function ($q) {
            $q->where('question_id', self::PLACE_OF_LIVING_QUESTION_ID);
        })->with(['answer' => function ($a) {
            $a->select('id', 'answer');
        }])->get();

        if (! $return->isEmpty()) {
            return $return->first()->answer->answer;
        }

        return '';
    }

    private function getPostal($user_id)
    {
        $answer = MatchingUserAnswer::where('user_id', $user_id)->whereHas('answer', function ($q) {
            $q->where('question_id', self::POSTAL_QUESTION_ID);
        })->first();

        $postal = "";
        if ($answer !== null) {
            $postal = $answer->answer_text;
            if (strlen($postal) > 2) {
                $postal = substr($postal, 0, 2) . str_repeat('x', strlen($postal) - 2);
            }
        }

        return $postal;
    }

    private function getMaritalStatus($user_id)
    {
        $return = MatchingUserAnswer::where('user_id', $user_id)->whereHas('answer', function ($q) {
            $q->where('question_id', self::MARITAL_QUESTION_ID);
        })->with(['answer'=>function ($a) {
            $a->select('id', 'answer');
        }])->get();
        if (! $return->isEmpty()) {
            return trim($return->first()->answer->answer, ' .');
        }

        return '';
    }

    private function getJob($user_id)
    {
        $jobString = "";

        $jobName = MatchingUserAnswer::where('user_id', $user_id)->where('answer_id', self::JOB_NAME_ID)->first();

        if (!empty($jobName) && !is_numeric($jobName->answer_text)) {
            $jobString .= $jobName->answer_text;
        }

        $matchingAnswer = MatchingUserAnswer::where('user_id', $user_id)
            ->whereHas('answer', function ($q) {
                $q->where('question_id', self::JOB_TYPE_ID);
            })->first();

        $jobType = "";
        if ($matchingAnswer !== null) {
            $jobType = $matchingAnswer->answer->answer;
        }

        if (!empty($jobString) && $jobType) {
            $jobString .= ", " . $jobType;
        } elseif ($jobType) {
            $jobString .= Str::title($jobType);
        }

        return $jobString;
    }

    private function getReligion($user_id)
    {
        $return = MatchingUserAnswer::where('user_id', $user_id)->whereHas('answer', function ($q) {
            $q->where('question_id', self::RELIGION_QUESTION_ID);
        })->with(['answer'=>function ($a) {
            $a->select('id', 'answer');
        }])->get();
        if (! $return->isEmpty()) {
            return $return->first()->answer->answer;
        }

        return '';
    }

    private function getNumberOfChildren($user_id)
    {
        $children = 0;

        $return = MatchingUserAnswer::where('user_id', $user_id)->whereHas('answer', function ($q) {
            $q->where('question_id', self::NUMBER_OF_BIOLOGICAL_CHILDREN_QUESTION_ID);
        })->with(['answer'=>function ($a) {
            $a->select('id', 'answer');
        }])->get();

        if (! $return->isEmpty()) {
            $children += intval($return->first()->answer_text);
        }

        $return = MatchingUserAnswer::where('user_id', $user_id)->whereHas('answer', function ($q) {
            $q->where('question_id', self::NUMBER_OF_ADOPTED_CHILDREN_QUESTION_ID);
        })->with(['answer'=>function ($a) {
            $a->select('id', 'answer');
        }])->get();

        if (! $return->isEmpty()) {
            $children += intval($return->first()->answer_text);
        }
        $return = MatchingUserAnswer::where('user_id', $user_id)->whereHas('answer', function ($q) {
            $q->where('question_id', self::NUMBER_OF_STEP_CHILDREN_QUESTION_ID);
        })->with(['answer'=>function ($a) {
            $a->select('id', 'answer');
        }])->get();

        if (! $return->isEmpty()) {
            $children += intval($return->first()->answer_text);
        }
        $return = MatchingUserAnswer::where('user_id', $user_id)->whereHas('answer', function ($q) {
            $q->where('question_id', self::NUMBER_OF_FOSTER_CHILDREN_QUESTION_ID);
        })->with(['answer'=>function ($a) {
            $a->select('id', 'answer');
        }])->get();

        if (! $return->isEmpty()) {
            $children += intval($return->first()->answer_text);
        }

        return $children;
    }

    private function getChildrenInHousehold($user_id)
    {
        $return = MatchingUserAnswer::where('user_id', $user_id)->whereHas('answer', function ($q) {
            $q->where('question_id', self::CHILDREN_IN_HOUSEHOLD_QUESTION_ID);
        })->get();
        if (! $return->isEmpty()) {
            return $return->first()->answer_text;
        }

        return '';
    }

    private function getAreChildrenAffected($user_id)
    {
        $matchingAnswerCodes = MatchingUserAnswer::where('user_id', $user_id)
            ->whereHas('answer', function ($q) {
                $q->whereIn('question_id', self::ARE_CHILDREN_AFFECTED_QUESTION_IDS);
            })
            ->with([
                'answer' => function ($a) {
                    $a->select('id', 'code');
                }
            ])
            ->get()
            ->pluck('answer.code');

        //for more information please check https://team.yesdevs.com/#/tasks/20201438
        $areAffected = $matchingAnswerCodes->filter(function ($code) {
            return strpos($code, "15,02") !== false ||
                strpos($code, "16,02") !== false ||
                strpos($code, "17,02") !== false;
        })->count() > 0;

        return $areAffected;
    }

    public function setVisibilities(Request $request)
    {
        $params = [];
        if (isset($request->job)) {
            $params['visibility_info_job'] = $request->job;
        }
        if (isset($request['religion'])) {
            $params['visibility_info_religion'] = $request->religion;
        }
        if (isset($request['children'])) {
            $params['visibility_info_children'] = $request->children;
        }
;
        DB::table('profile_visibilities')->updateOrInsert(
            ['user_id' => Auth::id()],
            $params
        );
    }

    private function getVisibilities($user_id)
    {
        $return = DB::table('profile_visibilities')->where('user_id', $user_id)->get()->first();

        return [
            'job' => $return->visibility_info_job,
            'religion' => $return->visibility_info_religion,
            'children' => $return->visibility_info_children, ];
    }

    private function getForeignAvatar($user_id)
    {
        $user = User::where('id', $user_id)->get()->first();

        return $user->avatar;
    }

    public function getPortrait()
    {
        $user = Auth::user();

        return response()->json(
            [
                'motto' => $this->getMotto($user->id),
                'avatar' => $user->avatar,
                'firstname'  => $user->nickname,
            ]
        );
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'file' => 'required|file|image|max:6144'
        ]);

        $user = Auth::user();
        $image = $request->file('file');
        $ext = $image->extension();

        $fileName = sprintf(
            '%s__%s.%s',
            $user->nickname,
            time(),
            $ext
        );

        $destinationPath = storage_path('app/public/uploads');
        $img = Image::make($image->path());
        $img->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'. $fileName);

        $user->avatar = 'uploads/' . $fileName;
        $user->save();

        $this->eventProfilePictureUploaded($user);

        return response()->json([
            'success' => $image->path(),
            'file' => $request->file('file'),
            'avatarUrl' => $user->avatar
        ]);
    }

    public function deleteAvatar(Request $request)
    {
        $user = Auth::user();
        $fileName = 'uploads/'.$user->nickname.'.png';
        $result = Storage::disk('public')->delete($fileName);

        $user->avatar = null;
        $user->save();

        return response()->json([
            'success' => $result,
            'avatarUrl' => $user->avatar
        ]);
    }

    protected function eventForeignProfileViewed(User $user) {
        $sendinBlue = new SendinBlueHandler($user);
        $sendinBlue->emitForeignProfileViewed();
    }

    protected function eventProfileSloganFilledOut(User $user) {
        $sendinBlue = new SendinBlueHandler($user);
        $sendinBlue->emitProfileSloganFilledOut();
    }

    protected function eventProfilePictureUploaded(User $user) {
        $sendinBlue = new SendinBlueHandler($user);
        $sendinBlue->emitProfilePictureUploaded();
    }
}
