import 'dart:async';
import 'package:audioplayers/audioplayers.dart';
import 'package:code_bright/utils/services/helper.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:audioplayers/audioplayers.dart' as audioplayers;

import '../../../utils/services/color.dart';

///Audio player --------

class PlayerWidget extends StatefulWidget {
  final List<dynamic> image;
  final AudioPlayer player;
  final int index;
  final int selectedIndex;

  const PlayerWidget({
    required this.player,
    required this.image,
    required this.index,
    required this.selectedIndex,
    super.key,
  });

  @override
  State<StatefulWidget> createState() {
    return _PlayerWidgetState();
  }
}

class _PlayerWidgetState extends State<PlayerWidget> {
  // PlayerState? _playerState;
  audioplayers.PlayerState? _playerState;
  Duration? _duration;
  Duration? _position;

  StreamSubscription? _durationSubscription;
  StreamSubscription? _positionSubscription;
  StreamSubscription? _playerCompleteSubscription;
  StreamSubscription? _playerStateChangeSubscription;

  bool get _isPlaying => _playerState == audioplayers.PlayerState.playing;

  bool get _isPaused => _playerState == audioplayers.PlayerState.paused;

  String get _durationText => _duration?.toString().split('.').first ?? '';

  String get _positionText => _position?.toString().split('.').first ?? '';

  AudioPlayer get player => widget.player;

  @override
  void initState() {
    super.initState();
    _playerState = player.state;
    player.getDuration().then(
          (value) => setState(() {
            _duration = value;
          }),
        );
    player.getCurrentPosition().then(
          (value) => setState(() {
            _position = value;
          }),
        );
  }

  @override
  void setState(VoidCallback fn) {
    if (mounted) {
      super.setState(fn);
    }
  }

  @override
  Future<void> dispose() async {
    await player.stop();

    _durationSubscription?.cancel();
    _positionSubscription?.cancel();
    _playerCompleteSubscription?.cancel();
    _playerStateChangeSubscription?.cancel();
    await player.dispose();
    super.dispose();
  }
  //
  // @override
  // void didUpdateWidget( PlayerWidget playerWidget) {
  //   super.didUpdateWidget(playerWidget);
  //   if (widget.selectedIndex != playerWidget.selectedIndex) {
  //     if (widget.selectedIndex == widget.index) {
  //       player.resume();
  //     } else {
  //       player.pause();
  //     }
  //   }
  // }

  @override
  Widget build(BuildContext context) {
    final color = Theme.of(context).primaryColor;
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: <Widget>[
        Container(
          decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(5),
              border: Border.all(color: CustomColor.starColor)),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              SizedBox(
                width: screenWidth(context) / 1.6,
                height: 40,
                child: Slider(
                  onChanged: (value) async {
                    final duration =
                        await _getDurationForIndex(widget.selectedIndex);
                    if (duration == null) {
                      return;
                    }
                    final position = value * duration.inMilliseconds.toDouble();
                    setState(() {
                      _position = Duration(milliseconds: position.round());
                    });
                    player.seek(_position!);
                    // if (widget.selectedIndex != widget.index) {
                    //   player.pause();
                    // } else if (widget.selectedIndex == widget.index) {
                    //   player.pause();
                    // }
                  },
                  value: (_position != null &&
                          _duration != null &&
                          _position!.inMilliseconds > 0 &&
                          _position!.inMilliseconds < _duration!.inMilliseconds)
                      ? _position!.inMilliseconds / _duration!.inMilliseconds
                      : 0.0,
                ),
              ),
              GestureDetector(
                  onTap: () {
                    _isPlaying ? _pause() : _play(widget.selectedIndex);
                  },
                  child: SizedBox(
                    height: 20,
                    width: 20,
                    child: _isPlaying
                        ? Icon(
                            Icons.pause,
                            color: color,
                          )
                        : Icon(
                            Icons.play_arrow,
                            color: color,
                          ),
                  )),
            ],
          ),
        ),
        Row(
          mainAxisAlignment: MainAxisAlignment.end,
          children: [
            Text(
              _position != null
                  ? '$_positionText / $_durationText'
                  : _duration != null
                      ? _durationText
                      : '',
              style: const TextStyle(fontSize: 13.0),
            ),
          ],
        ),
      ],
    );
  }

  void _initStreams() {
    player.getDuration().then(
          (value) => setState(() {
            _duration = value;
          }),
        );
    player.getCurrentPosition().then(
          (value) => setState(() {
            _position = value;
          }),
        );
    _durationSubscription = player.onDurationChanged.listen((duration) {
      setState(() => _duration = duration);
    });

    _positionSubscription = player.onPositionChanged.listen(
      (p) => setState(() => _position = p),
    );

    _playerCompleteSubscription = player.onPlayerComplete.listen((event) {
      setState(() {
        _playerState = audioplayers.PlayerState.stopped;
        _position = Duration.zero;
      });
    });

    _playerStateChangeSubscription =
        player.onPlayerStateChanged.listen((state) {
      setState(() {
        _playerState = state;
      });
    });
  }

  // Future<void> _play(int index) async {
  //   await player.setSource(UrlSource(widget.image[index]));
  //   _initStreams();
  //   await player.resume();
  //   setState(() => _playerState = audioplayers.PlayerState.playing);
  // }

  Future<void> _play(int index) async {
    printLog("_pause------$_isPlaying");
    if (_playerState == audioplayers.PlayerState.playing) {
      await player.pause();
      setState(() => _playerState = audioplayers.PlayerState.paused);
    }




    await player.setSource(UrlSource(widget.image[index]));
  Duration? duration = await player.getDuration();
  print("duration--------$duration");
    _initStreams();
    await player.resume();
    setState(() => _playerState = audioplayers.PlayerState.playing);
  }

  Future<void> _pause() async {
    printLog("_pause------$_isPlaying");
    await player.stop();
    setState(() => _playerState = audioplayers.PlayerState.paused);
  }

  Future<Duration?> _getDurationForIndex(int index) async {
    if (index >= 0 && index < widget.image.length) {
      String audioUrl = widget.image[index];

      AudioPlayer audioPlayer = AudioPlayer();
      await audioPlayer.setSourceUrl(audioUrl);
      Duration? duration = await audioPlayer.getDuration();
      return duration;
    }
    return null;
  }
}
