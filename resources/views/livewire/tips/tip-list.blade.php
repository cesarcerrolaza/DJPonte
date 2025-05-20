<div>
    <ul>
        @foreach($tips as $tip)
            <li>
               <x-tip-item :tipData="$tip" /> 
            </li>
        @endforeach
    </ul>
</div>


