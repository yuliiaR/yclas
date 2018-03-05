<?if (Theme::get('premium')==1):?>
    <?if (core::count($providers = Social::enabled_providers()) > 0) :?>
        <ul class="list-inline social-providers">
            <?foreach ($providers as $key => $provider) :?>     
                <li>
                    <?if(strtolower($key) == 'live'):?>
                        <a class="zocial <?=strtolower($key) == 'live' ? 'windows' : ''?>" href="<?=Route::url('default',array('controller'=>'social','action'=>'login','id'=>strtolower($key)))?>">
                            <?=$key?>
                        </a>
                    <?else:?>
                        <a class="zocial <?=strtolower($key)?>" href="<?=Route::url('default',array('controller'=>'social','action'=>'login','id'=>strtolower($key)))?>">
                            <?=$key?>
                        </a>
                    <?endif?>
                </li>
            <?endforeach?>
        </ul>
    <?endif?>
<?endif?>