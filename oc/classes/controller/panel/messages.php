<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel_Messages extends Auth_Frontcontroller {

    public function action_index()
    {
        $messages   = Model_Message::get_threads($this->user->id_user);
        $res_count  = $messages->count_all();
        
        //filter by status
        if (is_numeric(core::get('status')))
        {
            $messages->where('status', '=', core::get('status'));
        }

        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Messaging'))->set_url(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'index'))));
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Inbox')));
        
        Controller::$full_width = TRUE;

        if ($res_count > 0)
        {

            $pagination = Pagination::factory(array('view'              => 'oc-panel/crud/pagination',
                                                    'total_items'       => $res_count,
                                                    'items_per_page'    => core::config('advertisement.advertisements_per_page')
                                                    ))
                                    ->route_params(array(
                                                    'controller'    => $this->request->controller(),
                                                    'action'        => $this->request->action(),
                                                    ));

            Breadcrumbs::add(Breadcrumb::factory()->set_title(sprintf(__("Page %d"), $pagination->current_page)));

            $messages = $messages   ->order_by('status','asc')
                                    ->order_by('created','desc')
                                    ->limit($pagination->items_per_page)
                                    ->offset($pagination->offset)
                                    ->find_all();
            
            $this->template->styles = array('css/jquery.sceditor.default.theme.min.css' => 'screen');
                                    
            $this->template->scripts['footer'] = array( 'js/jquery.sceditor.bbcode.min.js',
                                                        'js/messages.js');
                                                                                
            $this->template->content = View::factory('oc-panel/pages/messages/index', array('messages'      => $messages,
                                                                                            'pagination'    => $pagination,
                                                                                            'user'          => $this->user));
        }
        else
        {

            $this->template->content = View::factory('oc-panel/pages/messages/index', array('messages'      => NULL,
                                                                                            'pagination'    => NULL,
                                                                                            'user'          => $this->user));
        }
    }
    
    public function action_message()
    {
        Controller::$full_width = TRUE;
        
        if ($this->request->param('id') !== NULL AND is_numeric($id_msg_thread = $this->request->param('id')))
        {
            $messages = Model_Message::get_thread($id_msg_thread, $this->user->id_user);

            if ($messages !== FALSE)
            {
                $msg_thread = new Model_Message();
                $msg_thread = $msg_thread->where('id_message', '=' , $this->request->param('id'))->find();
                
                // send reply message
                if ($this->request->post() AND Form::token('reply_message', TRUE))
                {
                    $validation = Validation::factory($this->request->post())->rule('message', 'not_empty');

                    if ($validation->check())
                    {
                        $ret = Model_Message::reply(core::post('message'), $this->user->id_user, $id_msg_thread, NULL);
                        
                        if ($ret !== FALSE)
                        {
                            //who is who? if from is the same then send to TO, else to from
                            if ($msg_thread->id_user_from == $this->user->id_user)
                            {
                                $user_to    = $msg_thread->to;
                                $user_from  = $msg_thread->from;
                            }
                            else
                            {
                                $user_to    = $msg_thread->from;
                                $user_from  = $msg_thread->to;
                            }

                            //email title
                            if ($msg_thread->id_ad !== NULL)
                                $email_title = $msg_thread->ad->title;
                            else
                                $email_title = sprintf(__('Direct message from %s'), $user_from->name);
                            
                            $user_to->email('messaging-reply', array(   '[TITLE]'       => $email_title,
                                                                        '[DESCRIPTION]' => core::post('message'),
                                                                        '[AD.NAME]'     => isset($msg_thread->ad->title) ? $msg_thread->ad->title : NULL,
                                                                        '[FROM.NAME]'   => $user_from->name,
                                                                        '[TO.NAME]'     => $user_to->name,
                                                                        '[URL.QL]'      => $user_to->ql('oc-panel', array(  'controller'    => 'messages',
                                                                                                                            'action'        => 'message',
                                                                                                                            'id'            => $this->request->param('id'))))
                                            );
                            
                            // we are updating field of visit table (contact)
                            if ($msg_thread->id_ad !== NULL)
                            {
                                $visit = new Model_Visit();

                                $visit->where('id_ad', '=', $msg_thread->id_ad)
                                                ->where('id_user', '=', $user_from->id_user)
                                                ->order_by('created', 'desc')
                                                ->limit(1)->find();
                                if ($visit->loaded())
                                {
                                    $visit->contacted = 1;
                                    $visit->created = Date::unix2mysql();
                                    try {
                                        $visit->save();
                                    } catch (Exception $e) {
                                        //throw 500
                                        throw HTTP_Exception::factory(500,$e->getMessage());
                                    }
                                }
                            }

                            Alert::set(Alert::SUCCESS, __('Reply created.'));
                            $this->redirect(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'message', 'id' => Request::current()->param('id'))));
                        }
                        else
                            Alert::set(Alert::ERROR, __('Message not sent'));
                    }
                    else
                    {
                        $errors = $validation->errors('message');
                    }
                }
                
                Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Messaging'))->set_url(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'index'))));
                if ($msg_thread->id_ad !== NULL)
                    Breadcrumbs::add(Breadcrumb::factory()->set_title($msg_thread->ad->title));
                else
                    Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Direct Message')));
                
                $this->template->styles = array('css/jquery.sceditor.default.theme.min.css' => 'screen');
                
                $this->template->scripts['footer'] = array( 'js/jquery.sceditor.bbcode.min.js',
                                                            'js/messages.js');
                
                $this->template->content = View::factory('oc-panel/pages/messages/message', array(  'msg_thread'    => $msg_thread,
                                                                                                    'messages'      => $messages,
                                                                                                    'user'          => $this->user));
            }
            else
            {
                Alert::set(Alert::ERROR, __('Message not found'));
                $this->redirect(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'index')));
            }
        }
        else
        {
            Alert::set(Alert::ERROR, __('Message not found'));
            $this->redirect(Route::url('oc-panel', array('controller' => 'messages', 'action' => 'index')));
        }
    }

}
