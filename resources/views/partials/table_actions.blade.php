<div class="list-icons">
    <div class="dropdown">
        <a href="#" class="list-icons-item" data-toggle="dropdown">
            <i class="icon-menu9"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a href="{{route('galleries.edit',$id)}}" class="dropdown-item  @cannot('galleries::role.edit') disabled @endcannot"><i class="icon-pencil5"></i>@lang('admin/general.actions.edit')</a>
            <a href="{{route('galleries.manage',$id)}}" class="dropdown-item  @cannot('galleries::role.manage') disabled @endcannot"><i class="icon-images3 "></i>@lang('admin/general.actions.manage')</a>
            <div class="dropdown-divider"></div>
            <a href="#" data-url ="{{route('galleries.destroy',$id)}}" class="dropdown-item text-danger delete @haveChildren($id) disabled @endhaveChildren @cannot('galleries::role.delete') disabled @endcannot"><i class="icon-trash-alt "></i>@lang('admin/general.actions.delete')</a>
        </div>
    </div>
</div>
