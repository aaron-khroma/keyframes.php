# keyframes.php
SVG animations are extremely powerful and compact, but writing them is a confusing mess that requires careful planning. With this utility, the keyframes of an SVG animation can be written out in human-readable PHP Arrays, which are then compiled into browser-readable SVG animation attributes.

## Usage
The keyframes function can be inserted into any SVG tag that defines an animation (i.e. `animate` and `animateTransform`). It accepts an Array of frames, with each frame containing some or all of the keys listed below:
- easeE => The bezier handle data used to conclude the easing from the last keyframe
- value => The value of the attribute at this keyframe
- length => The time in seconds until the next keyframe
- easeS => The bezier handle data used to start easing to the next keyframe

The following rules apply to the interpretation of these keys:
- easeE: not required on the first frame
- easeS/length: not required on the final frame

The function also accepts an optional delay value in seconds as a second argument. If this is provided, then an additional keyframe will be added at the beginning of the animation with that duration. This allows animations with a delayed start to be triggered on elements with their `begin` attribute set to `indefinite`.
