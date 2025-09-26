let client = (function () {

    let connectionTimer = 5000,
        socketUrl = 'wss://docscreen.rnova.org/ws/',
        conn,
        uniqCache,
        roomId,
        isRegistered = false;

    /**
     *
     */
    function init(room_id = null) {
        roomId = room_id;
        createSocketConnection();
        setSocketConnection();
        uniqCache = setUniqCache();
    }

    /**
     *
     */
    function setSocketConnection() {
        clearSocketConnection();
        connectionTimer = setInterval(() => {
            createSocketConnection();
        }, 5000);
    }

    /**
     *
     */
    function createSocketConnection() {
        if (!socketUrl) {
            return;
        }
        conn = new WebSocket(socketUrl);
        conn.onopen = function (e) {
            clearSocketConnection();
            register();
        };
        conn.onclose = function (e) {
            setSocketConnection();
        };
        conn.onerror = function (e) {
            setSocketConnection();
        };
        conn.onmessage = function (e) {
            handleMessage(e.data);
        };
    }

    /**
     *
     */
    function closeConnection() {
        if (!conn) {
            return;
        }
        conn.close();
    }

    /**
     *
     */
    function clearSocketConnection() {
        clearInterval(connectionTimer);
        connectionTimer = null;
    }

    /**
     *
     */
    function setUniqCache() {
        return (new Date).getTime();
    }

    /**
     *
     */
    function register() {
        console.log('register')
        send('register', {cache: uniqCache, roomId: roomId});
        isRegistered = true;
        //
    }

    function listen() {
        console.log('client listen')
    }

    /**
     * @param message
     */
    function handleMessage(message) {
        console.log('message', message)
        return;
        var data = JSON.parse(message),
            method = data['method'];
        if (!method) {
            return false;
        }

        /*switch (method) {
            case 'call':
                call.handle(data);
                break;
            case 'direct':
                handleDirectMessage(data);
                break;
            case 'self':
                handleSelfMessage(data);
                break;
            case 'notification':
                handleNotification(data);
                break;
            case 'appointment':
                handleAppointment(data);
                break;

        }*/
        return true;
    }

    /**
     *
     * @param data
     */
    // function handleNotification(data) {
    //     try {
    //         var event = data['event'];
    //         switch (event) {
    //             case 'update':
    //                 userNotification.updateNotification(data);
    //                 break;
    //             case 'list':
    //                 userNotification.init();
    //                 break;
    //         }
    //     } catch (e) {
    //
    //     }
    // }

    /**
     *
     * @param data
     */
    // function handleAppointment(data) {
    //     try {
    //         var action = data.event;
    //         switch (action) {
    //             case 'notificationManager':
    //                 notification.showAppointment(data);
    //                 dashboard.getData();
    //                 break;
    //         }
    //     } catch (e) {
    //     }
    // }

    /**
     * @param data
     */
    // function handleSelfMessage(data) {
    //     try {
    //         var action = data.action;
    //         switch (action) {
    //             case 'modal':
    //                 callsRouter.hideCall();
    //                 break;
    //             case 'call_panel':
    //                 if (data.uniqCache != uniqCache) {
    //                     call.togglePanel(true);
    //                 }
    //         }
    //     }
    //     catch (e) {
    //     }
    // }

    /**
     * @param method
     * @param data
     */
    function send(method, data) {
        data['method'] = method;
        data['uniqCache'] = uniqCache;
        conn.send(JSON.stringify(data));
    }

    /**
     *
     */
    // function handleDirectMessage(data) {
    //     try {
    //         var event = data.event;
    //         switch (event) {
    //             case 'comments':
    //                 comments.update(data.model, data.id);
    //                 break;
    //             case 'call':
    //                 call.showActiveCalls();
    //                 break;
    //             case 'missed_calls':
    //                 //userCallSettings.updateCountCall();
    //                 break;
    //         }
    //     }
    //     catch (e) {
    //     }
    // }

    //
    return {
        init, send, closeConnection
    }

})();
