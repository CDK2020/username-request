/*
 *
 *  This file is part of fof/username-request.
 *
 *  Copyright (c) 2019 FriendsOfFlarum.
 *
 *  For the full copyright and license information, please view the LICENSE.md
 *  file that was distributed with this source code.
 *
 */

import { extend } from 'flarum/common/extend';
import app from 'flarum/common/app';
import LinkButton from 'flarum/common/components/LinkButton';
import UserPage from 'flarum/forum/components/UserPage';

export default function () {
    extend(UserPage.prototype, 'navItems', function (items) {
        if (this.user.usernameHistory()) {
            items.add(
                'username-requests',
                LinkButton.component(
                    {
                        href: app.route('username_history', { username: this.user.username() }),
                        icon: 'fas fa-user-edit',
                    },
                    app.translator.trans('fof-username-request.forum.user.name_history_link')
                )
            );
        }
    });
}
